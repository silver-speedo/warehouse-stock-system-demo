<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginated(): LengthAwarePaginator
    {
        return Product::query()->fastPaginate(request('page.size', 10));
    }

    public function all(): ?Collection
    {
        return Product::all();
    }

    public function find(string $uuid): ?Product
    {
        return Product::query()->find($uuid);
    }

    public function create(array $data): Product
    {
        return Product::query()->create($data);
    }

    public function update(string $uuid, array $data): bool
    {
        return Product::query()->find($uuid)->update($data) > 0;
    }

    public function delete(string $uuid): bool
    {
        return Product::query()->find($uuid)->delete() > 0;
    }
}
