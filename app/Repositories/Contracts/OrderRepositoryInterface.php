<?php

namespace App\Repositories\Contracts;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function paginated(): LengthAwarePaginator;

    public function all(): ?Collection;

    public function find(string $uuid): ?Order;

    public function create(array $attributes): Order;

    public function update(string $uuid, array $data): bool;

    public function delete(string $uuid): bool;

    public function addItem(Order $order, string $productUuid, array $itemData): void;
}
