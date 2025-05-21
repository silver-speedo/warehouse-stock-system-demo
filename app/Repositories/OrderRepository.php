<?php

namespace App\Repositories;

use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function paginated(): LengthAwarePaginator
    {
        return Order::query()->orderBy('uuid')->fastPaginate(request('page.size', 10));
    }

    public function all(): ?Collection
    {
        return Order::all();
    }

    public function find(string $uuid): ?Order
    {
        return Order::with('products')->find($uuid);
    }

    public function create(array $attributes): Order
    {
        return Order::query()->create($attributes);
    }

    public function update(string $uuid, array $data): bool
    {
        return Order::query()->find($uuid)->update($data) > 0;
    }

    public function delete(string $uuid): bool
    {
        return Order::query()->find($uuid)->delete() > 0;
    }

    public function addItem(Order $order, string $productUuid, array $itemData): void
    {
        $order->products()->syncWithPivotValues($productUuid, $itemData, false);
    }
}
