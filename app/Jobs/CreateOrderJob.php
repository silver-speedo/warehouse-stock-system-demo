<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Events\CreateOrderFailed;
use App\Events\CreateOrderSuccess;
use App\Models\Product;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\StockRepositoryInterface;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateOrderJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public array $productUuid;

    public array $quantity;

    public function __construct(array $productUuid, array $quantity)
    {
        $this->productUuid = $productUuid;
        $this->quantity = $quantity;
    }

    public function handle(OrderRepositoryInterface $orders, StockRepositoryInterface $stock): void
    {
        try {
            DB::beginTransaction();

            $total = 0;
            $order = $orders->create([
                'order_status' => OrderStatus::PLACED->value,
                'total' => $total,
            ]);

            foreach ($this->productUuid as $id => $uuid) {
                $product = Product::query()->findOrFail($uuid);
                $itemTotal = $product->price * $this->quantity[$id];

                $warehouseId = $stock->deductFromBestWarehouse($product->uuid, $this->quantity[$id]);

                $orders->addItem($order, $product->uuid, [
                    'warehouse_uuid' => $warehouseId,
                    'quantity' => $this->quantity[$id],
                    'price' => $product->price,
                    'total' => $itemTotal,
                ]);

                $total += $itemTotal;
            }

            $order->total = $total;
            $order->save();

            DB::commit();

            event(new CreateOrderSuccess($order));
        } catch (Exception $e) {
            DB::rollBack();

            event(new CreateOrderFailed($e->getMessage()));
        }
    }
}
