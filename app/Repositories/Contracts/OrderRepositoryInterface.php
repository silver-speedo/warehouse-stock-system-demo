<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function all(): LengthAwarePaginator;

    public function find(string $uuid): ?Order;

    public function create(array $attributes): Order;

    public function update(string $uuid, array $data): bool;

    public function delete(string $uuid): bool;

    public function addItem(Order $order, string $productUuid, array $itemData): void;
}
