<?php

namespace App\Repositories\Contracts;

use App\Models\Warehouse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface WarehouseRepositoryInterface
{
    public function paginated(): LengthAwarePaginator;

    public function all(): ?Collection;

    public function find(string $uuid): ?Warehouse;

    public function create(array $data): Warehouse;

    public function update(string $uuid, array $data): bool;

    public function delete(string $uuid): bool;
}
