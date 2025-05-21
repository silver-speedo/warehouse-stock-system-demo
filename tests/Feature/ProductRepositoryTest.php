<?php

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Pagination\LengthAwarePaginator;

use function Pest\Laravel\{assertDatabaseEmpty, assertDatabaseHas};

beforeEach(function () {
    $this->repo = new ProductRepository();
});

it('can create a product', function () {
    $product = Product::factory()->make()->toArray();

    $this->repo->create($product);

    assertDatabaseHas('products', [
        'title' => $product['title'],
        'description' => $product['description'],
        'price' => $product['price'],
    ]);
});

it('can find a product by uuid', function () {
    $product = Product::factory()->create();

    $found = $this->repo->find($product->uuid);

    expect($found)
        ->not->toBeNull()
        ->and($found->uuid)
        ->toBe($product->uuid);
});

it('can update a product', function () {
    $product = Product::factory()->create(['title' => 'old title']);

    $this->repo->update($product->uuid, ['title' => 'new title']);

    assertDatabaseHas('products', [
        'title' => 'new title',
    ]);
});

it('can delete a product', function () {
    $product = Product::factory()->create();

    $this->repo->delete($product->uuid);

    assertDatabaseEmpty('products');
});

it('can return all products', function () {
    Product::factory()->count(3)->create();

    $all = $this->repo->all();

    expect($all)
        ->toHaveCount(3);
});

it('can paginate products', function () {
    Product::factory()->count(15)->create();

    $paginated = $this->repo->paginated();

    expect($paginated)
        ->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginated->count())
        ->toBe(10);
});
