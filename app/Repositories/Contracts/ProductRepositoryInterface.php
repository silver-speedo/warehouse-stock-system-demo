<?php

namespace App\Repositories\Contracts;

use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface ProductRepositoryInterface
{
    public function paginated(): LengthAwarePaginator;

    public function all(): ?Collection;

    public function find(string $uuid): ?Product;

    public function create(array $data): Product;

    public function update(string $uuid, array $data): bool;

    public function delete(string $uuid): bool;
}
