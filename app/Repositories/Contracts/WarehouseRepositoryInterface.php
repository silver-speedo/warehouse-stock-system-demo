<?php

namespace App\Repositories\Contracts;

use App\Models\Warehouse;
use Illuminate\Pagination\LengthAwarePaginator;

interface WarehouseRepositoryInterface
{
    public function all(): LengthAwarePaginator;

    public function find(string $uuid): ?Warehouse;

    public function create(array $attributes): Warehouse;

    public function update(string $uuid, array $data): bool;

    public function delete(string $uuid): bool;
}
