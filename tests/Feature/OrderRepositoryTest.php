<?php

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use App\Repositories\OrderRepository;
use Illuminate\Pagination\LengthAwarePaginator;

use function Pest\Laravel\{assertDatabaseEmpty, assertDatabaseHas};

beforeEach(function () {
    $this->repo = new OrderRepository();
});

it('can create an order', function () {
    $order = Order::factory()->make()->toArray();

    $this->repo->create($order);

    assertDatabaseHas('orders', [
        'order_status' => OrderStatus::PLACED,
    ]);
});

it('can find an order by uuid', function () {
    $order = Order::factory()->create();

    $found = $this->repo->find($order->uuid);

    expect($found)
        ->not->toBeNull()
        ->and($found->uuid)
        ->toBe($order->uuid);
});

it('can update an order', function () {
    $order = Order::factory()->create(['order_status' => OrderStatus::PLACED]);

    $this->repo->update($order->uuid, ['order_status' => OrderStatus::CANCELLED]);

    assertDatabaseHas('orders', [
        'order_status' => OrderStatus::CANCELLED,
    ]);
});

it('can delete an order', function () {
    $order = Order::factory()->create();

    $this->repo->delete($order->uuid);

    assertDatabaseEmpty('orders');
});

it('can return all orders', function () {
    Order::factory()->count(3)->create();

    $all = $this->repo->all();

    expect($all)
        ->toHaveCount(3);
});

it('can paginate orders', function () {
    Order::factory()->count(15)->create();

    $paginated = $this->repo->paginated();

    expect($paginated)
        ->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginated->count())
        ->toBe(10);
});

it('can add item to order', function () {
    $order = Order::factory()->create();
    $product = Product::factory()->create();
    $warehouse = Warehouse::factory()->create();

    $this->repo->addItem($order, $product->uuid, [
        'warehouse_uuid' => $warehouse->uuid,
        'quantity' => 2,
        'price' => $product->price,
        'total' => $product->price * 2,
    ]);

    assertDatabaseHas('order_products', [
        'order_uuid' => $order->uuid,
        'product_uuid' => $product->uuid,
        'warehouse_uuid' => $warehouse->uuid,
        'quantity' => 2,
        'price' => $product->price,
        'total' => $product->price * 2,
    ]);
});
