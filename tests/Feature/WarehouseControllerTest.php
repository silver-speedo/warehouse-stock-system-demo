<?php

use App\Models\Warehouse;

use function Pest\Laravel\{getJson};

beforeEach(function () {
    $this->endpoint = '/v1/warehouses';
});

it('returns a list of warehouses from the index endpoint', function () {
    $warehouses = Warehouse::factory()->count(2)->create();

    $response = getJson("{$this->endpoint}/index");

    $response->assertOk()
        ->assertJsonFragment([
            $warehouses[0]->uuid => $warehouses[0]->name,
            $warehouses[1]->uuid => $warehouses[1]->name,
        ]);
});

it('returns a paginated list from the list endpoint', function () {
    Warehouse::factory()->count(15)->create();

    $response = getJson("{$this->endpoint}");

    $response->assertOk()
        ->assertJsonStructure([
            'data',
            'current_page',
            'last_page',
            'per_page',
        ]);
});
