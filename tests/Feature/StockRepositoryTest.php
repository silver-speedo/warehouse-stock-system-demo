<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use App\Repositories\StockRepository;

beforeEach(function () {
    $this->repo = new StockRepository();
});

it('returns total stock quantity from all warehouses', function () {
    $product = Product::factory()->create();
    $warehouseA = Warehouse::factory()->create();
    $warehouseB = Warehouse::factory()->create();

    $product->warehouses()->syncWithPivotValues($warehouseA->uuid, ['quantity' => 10, 'threshold' => 2]);
    $product->warehouses()->syncWithPivotValues($warehouseB->uuid, ['quantity' => 5, 'threshold' => 1], false);

    $total = $this->repo->getTotalQuantity($product->uuid);

    expect($total)->toBe(15);
});

it('returns total threshold from all warehouses', function () {
    $product = Product::factory()->create();
    $warehouseA = Warehouse::factory()->create();
    $warehouseB = Warehouse::factory()->create();

    $product->warehouses()->syncWithPivotValues($warehouseA->uuid, ['quantity' => 10, 'threshold' => 3]);
    $product->warehouses()->syncWithPivotValues($warehouseB->uuid, ['quantity' => 5, 'threshold' => 2], false);

    $total = $this->repo->getTotalThreshold($product->uuid);

    expect($total)->toBe(5);
});

it('returns allocated quantity from non-dispatched orders', function () {
    $product = Product::factory()->create();
    $warehouse = Warehouse::factory()->create();

    Order::factory()->create(['order_status' => OrderStatus::PLACED])
        ->products()->syncWithPivotValues(
            $product->uuid,
            [
                'warehouse_uuid' => $warehouse->uuid,
                'quantity' => 4 ,
                'price' => 100,
                'total' => 400,
            ]
        );

    Order::factory()->create(['order_status' => OrderStatus::DISPATCHED])
        ->products()->syncWithPivotValues(
            $product->uuid,
            [
                'warehouse_uuid' => $warehouse->uuid,
                'quantity' => 10,
                'price' => 25,
                'total' => 250,
            ]
        );

    $allocated = $this->repo->getAllocatedQuantity($product->uuid);

    expect($allocated)->toBe(4);
});

it('returns physical quantity as total + allocated', function () {
    $product = Product::factory()->create();
    $warehouse = Warehouse::factory()->create();

    $product->warehouses()->syncWithPivotValues($warehouse->uuid, ['quantity' => 5, 'threshold' => 0]);

    Order::factory()->create(['order_status' => OrderStatus::PLACED])
        ->products()->syncWithPivotValues(
            $product->uuid,
            [
                'warehouse_uuid' => $warehouse->uuid,
                'quantity' => 10,
                'price' => 25,
                'total' => 250,
            ]
        );

    $physical = $this->repo->getPhysicalQuantity($product->uuid);

    expect($physical)->toBe(15);
});

it('returns immediate dispatchable stock', function () {
    $product = Product::factory()->create();
    $warehouse = Warehouse::factory()->create();

    $product->warehouses()->syncWithPivotValues($warehouse->uuid, ['quantity' => 10, 'threshold' => 3]);

    $dispatchable = $this->repo->getImmediateDispatchQuantity($product->uuid);

    expect($dispatchable)->toBe(7);
});

it('returns breakdown of quantity + threshold per warehouse', function () {
    $product = Product::factory()->create();
    $warehouse = Warehouse::factory()->create();

    $product->warehouses()->syncWithPivotValues($warehouse->uuid, ['quantity' => 6, 'threshold' => 2]);

    $breakdown = $this->repo->getWarehouseBreakdown($product->uuid);

    expect($breakdown)->toHaveCount(1)
        ->and($breakdown->first())->toMatchArray([
            'name' => $warehouse->name,
            'slug' => $warehouse->slug,
            'quantity' => 6,
            'threshold' => 2,
        ]);
});

it('deducts from best-stocked warehouse above threshold', function () {
    $product = Product::factory()->create();
    $warehouse = Warehouse::factory()->create();

    $product->warehouses()->syncWithPivotValues($warehouse->uuid, ['quantity' => 10, 'threshold' => 2]);

    $used = $this->repo->deductFromBestWarehouse($product->uuid, 5);

    expect($used)->toBe($warehouse->uuid);

    $pivot = $product->warehouses()->first()->pivot;
    expect($pivot->quantity)->toBe(5);
});

it('throws an exception if not enough stock in any warehouse', function () {
    $product = Product::factory()->create();
    $warehouse = Warehouse::factory()->create();

    $product->warehouses()->syncWithPivotValues($warehouse->uuid, ['quantity' => 5, 'threshold' => 4]);

    $this->expectException(Exception::class);
    $this->expectExceptionMessage("Not enough stock for product {$product->uuid}");

    $this->repo->deductFromBestWarehouse($product->uuid, 3);
});
