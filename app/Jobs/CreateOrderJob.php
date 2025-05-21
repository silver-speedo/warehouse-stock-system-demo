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
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    public string $productUuid;
    public int $quantity;

    public function __construct(string $productUuid, int $quantity)
    {
        $this->productUuid = $productUuid;
        $this->quantity = $quantity;
    }

    public function handle(OrderRepositoryInterface $orders, StockRepositoryInterface $stock): void
    {
        try {
            DB::beginTransaction();

            $product = Product::query()->findOrFail($this->productUuid);
            $total = $product->price * $this->quantity;

            $stock->deductFromBestWarehouse($this->productUuid, $this->quantity);

            $order = $orders->create([
                'order_status' => OrderStatus::PLACED->value,
                'total' => $total,
            ]);

            $orders->addItem($order, $product->uuid, [
                'quantity' => $this->quantity,
                'price' => $product->price,
                'total' => $total,
            ]);

            DB::commit();

            event(new CreateOrderSuccess($order));
        } catch (Exception $e) {
            DB::rollBack();

            event(new CreateOrderFailed($e->getMessage()));
        }
    }
}

