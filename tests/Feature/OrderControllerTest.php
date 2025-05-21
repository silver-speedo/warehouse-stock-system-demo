<?php

use App\Enums\OrderStatus;
use App\Jobs\CreateOrderJob;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Queue;

use function Pest\Laravel\{getJson, postJson};

beforeEach(function () {
    $this->endpoint = '/v1/orders';
});

it('returns a list of orders from the index endpoint', function () {
    $orders = Order::factory()->count(2)->create([
        'order_status' => OrderStatus::PLACED,
    ]);

    $response = getJson("{$this->endpoint}/index");

    $response->assertOk()
        ->assertJsonFragment([
            $orders[0]->uuid => OrderStatus::PLACED,
            $orders[1]->uuid => OrderStatus::PLACED,
        ]);
});

it('returns a paginated list from the list endpoint', function () {
    Order::factory()->count(15)->create();

    $response = getJson("{$this->endpoint}");

    $response->assertOk()
        ->assertJsonStructure([
            'data',
            'current_page',
            'last_page',
            'per_page',
        ]);
});

it('fails to create an order with missing data', function () {
    $response = postJson("{$this->endpoint}", []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['product_uuid', 'quantity']);
});

it('creates a new order and dispatches CreateOrderJob', function () {
    Queue::fake();

    $product = Product::factory()->create();

    $payload = [
        'product_uuid' => [$product->uuid],
        'quantity' => [2],
    ];

    $response = postJson("{$this->endpoint}", $payload);

    $response->assertCreated()
        ->assertJson([
            'message' => 'Order placed successfully!',
        ]);

    Queue::assertPushed(CreateOrderJob::class, function ($job) use ($payload) {
        return $job->productUuid === $payload['product_uuid'] &&
            $job->quantity === $payload['quantity'];
    });
});
