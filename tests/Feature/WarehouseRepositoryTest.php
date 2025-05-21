<?php

use App\Models\Warehouse;
use App\Repositories\WarehouseRepository;
use Illuminate\Pagination\LengthAwarePaginator;

use function Pest\Laravel\{assertDatabaseEmpty, assertDatabaseHas};

beforeEach(function () {
    $this->repo = new WarehouseRepository();
});

it('can create a warehouse', function () {
    $warehouse = Warehouse::factory()->make()->toArray();

    $this->repo->create($warehouse);

    assertDatabaseHas('warehouses', [
        'name' => $warehouse['name'],
        'slug' => $warehouse['slug'],
    ]);
});

it('can find a warehouse by uuid', function () {
    $warehouse = Warehouse::factory()->create();

    $found = $this->repo->find($warehouse->uuid);

    expect($found)
        ->not->toBeNull()
        ->and($found->uuid)
        ->toBe($warehouse->uuid);
});

it('can update a warehouse', function () {
    $warehouse = Warehouse::factory()->create(['name' => 'old name']);

    $this->repo->update($warehouse->uuid, ['name' => 'new name']);

    assertDatabaseHas('warehouses', [
        'name' => 'new name',
    ]);
});

it('can delete a warehouse', function () {
    $warehouse = Warehouse::factory()->create();

    $this->repo->delete($warehouse->uuid);

    assertDatabaseEmpty('warehouses');
});

it('can return all warehouses', function () {
    Warehouse::factory()->count(3)->create();

    $all = $this->repo->all();

    expect($all)
        ->toHaveCount(3);
});

it('can paginate warehouses', function () {
    Warehouse::factory()->count(15)->create();

    $paginated = $this->repo->paginated();

    expect($paginated)
        ->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginated->count())
        ->toBe(10);
});
