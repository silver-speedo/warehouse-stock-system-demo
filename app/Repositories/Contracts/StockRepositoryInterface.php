<?php

namespace App\Repositories\Contracts;

use Illuminate\Support\Collection;

interface StockRepositoryInterface
{
    public function getAllocatedQuantity(string $productUuid): int;

    public function getTotalQuantity(string $productUuid): int;

    public function getTotalThreshold(string $productUuid): int;

    public function getPhysicalQuantity(string $productUuid): int;

    public function getImmediateDispatchQuantity(string $productUuid): int;

    public function getWarehouseBreakdown(string $productUuid): Collection;

    public function deductFromBestWarehouse(string $productUuid, int $quantity): string;
}
