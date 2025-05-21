<?php

namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Models\Product;
use App\Models\Warehouse;
use App\Repositories\Contracts\StockRepositoryInterface;
use Exception;
use Illuminate\Support\Collection;

class StockRepository implements StockRepositoryInterface
{
    public function getAllocatedQuantity(string $productUuid): int
    {
        return Product::query()
            ->find($productUuid)
            ->orders()
            ->where('order_status', '!=', OrderStatus::DISPATCHED->value)
            ->sum('order_products.quantity');
    }

    public function getTotalQuantity(string $productUuid): int
    {
        return Product::query()
            ->find($productUuid)
            ->warehouses()
            ->sum('product_warehouses.quantity');
    }

    public function getTotalThreshold(string $productUuid): int
    {
        return Product::query()
            ->find($productUuid)
            ->warehouses()
            ->sum('product_warehouses.threshold');
    }

    public function getPhysicalQuantity(string $productUuid): int
    {
        return $this->getTotalQuantity($productUuid) + $this->getAllocatedQuantity($productUuid);
    }

    public function getImmediateDispatchQuantity(string $productUuid): int
    {
        return $this->getTotalQuantity($productUuid) - $this->getTotalThreshold($productUuid);
    }

    public function getWarehouseBreakdown(string $productUuid): Collection
    {
        return Warehouse::query()->whereHas('products', function ($query) use ($productUuid) {
            $query->where('products.uuid', $productUuid);
        })
            ->with(['products' => function ($query) use ($productUuid) {
                $query->where('products.uuid', $productUuid)
                    ->select('products.uuid');
            }])
            ->get()
            ->map(function ($warehouse) use ($productUuid) {
                $product = $warehouse->products->firstWhere('uuid', $productUuid);

                return [
                    'name' => $warehouse->name,
                    'slug' => $warehouse->slug,
                    'quantity' => $product?->pivot->quantity ?? 0,
                    'threshold' => $product?->pivot->threshold ?? 0,
                ];
            });
    }

    /**
     * @throws Exception
     */
    public function deductFromBestWarehouse(string $productUuid, int $quantity): string
    {
        $stocks = Product::query()
            ->find($productUuid)
            ->warehouses()
            ->orderByDesc('quantity')
            ->get();

        foreach ($stocks as $stock) {
            if (($stock->pivot->quantity - $stock->pivot->threshold) >= $quantity) {
                $stock->pivot->quantity -= $quantity;
                $stock->pivot->save();

                return $stock->uuid;
            }
        }

        throw new Exception('Not enough stock for product ' . $productUuid);
    }
}
