<?php

namespace App\Repositories;

use App\Models\Warehouse;
use App\Repositories\Contracts\WarehouseRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WarehouseRepository implements WarehouseRepositoryInterface
{
    public function paginated(): LengthAwarePaginator
    {
        return Warehouse::query()->fastPaginate(request('page.size', 10));
    }

    public function all(): ?Collection
    {
        return Warehouse::all();
    }

    public function find(string $uuid): ?Warehouse
    {
        return Warehouse::query()->find($uuid);
    }

    public function create(array $attributes): Warehouse
    {
        return Warehouse::query()->create($attributes);
    }

    public function update(string $uuid, array $data): bool
    {
        return Warehouse::query()->find($uuid)->update($data) > 0;
    }

    public function delete(string $uuid): bool
    {
        return Warehouse::query()->find($uuid)->delete() > 0;
    }
}
