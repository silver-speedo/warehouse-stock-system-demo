<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(): LengthAwarePaginator
    {
        return Product::query()->fastPaginate(request('page.size', 10));
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
