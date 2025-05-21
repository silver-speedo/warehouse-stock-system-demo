<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;

use function Pest\Laravel\{getJson};

beforeEach(function () {
    $this->endpoint = '/v1/products';
});

it('returns list of products from the index endpoint', function () {
    $products = Product::factory()->count(2)->create();

    $response = getJson("{$this->endpoint}/index");

    $response->assertOk()
        ->assertJsonFragment([
            $products[0]->uuid => $products[0]->title,
            $products[1]->uuid => $products[1]->title,
        ]);
});

it('returns a paginated list from the list endpoint', function () {
    $warehouseA = Warehouse::factory()->create();
    $warehouseB = Warehouse::factory()->create();

    $product = Product::factory()->create();
    $product->warehouses()->syncWithPivotValues(
        $warehouseA->uuid,
        ['quantity' => 10, 'threshold' => 2]
    );
    $product->warehouses()->syncWithPivotValues(
        $warehouseB->uuid,
        ['quantity' => 5, 'threshold' => 1],
        false
    );

    Order::factory()->create()->products()->syncWithPivotValues(
        $product->uuid,
        [
            'warehouse_uuid' => $warehouseA->uuid,
            'quantity' => 3,
            'price' => $product->price,
            'total' => $product->price * 3,
        ]
    );

    $response = getJson("{$this->endpoint}");

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [[
                'uuid',
                'title',
                'price',
                'total_quantity',
                'allocated_to_orders',
                'physical_quantity',
                'total_threshold',
                'immediate_dispatch',
                'warehouses' => [[
                    'name',
                    'slug',
                    'quantity',
                    'threshold',
                ]],
            ]],
            'current_page',
            'last_page',
            'per_page',
        ])
        ->assertJsonFragment([
            'total_quantity' => 15,
            'allocated_to_orders' => 3,
            'physical_quantity' => 18,
            'total_threshold' => 3,
            'immediate_dispatch' => 12,
        ]);
});

it('returns a single product from the view endpoint', function () {
    $warehouse = Warehouse::factory()->create();

    $product = Product::factory()->create();
    $product->warehouses()->syncWithPivotValues(
        $warehouse->uuid,
        ['quantity' => 7, 'threshold' => 2]
    );

    Order::factory()->create()->products()->syncWithPivotValues(
        $product->uuid,
        [
            'warehouse_uuid' => $warehouse->uuid,
            'quantity' => 2,
            'price' => $product->price,
            'total' => $product->price * 2,
        ]
    );

    $response = getJson("{$this->endpoint}/{$product->uuid}");

    $response->assertOk()
        ->assertJson([
            'product' => [
                'uuid' => $product->uuid,
                'title' => $product->title,
            ],
            'allocated' => 2,
            'threshold' => 2,
            'physical' => 9,
            'dispatchable' => 5,
        ]);
});
