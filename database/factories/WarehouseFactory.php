<?php

namespace Database\Factories;

use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Warehouse>
 */
class WarehouseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'slug' => fake()->unique()->slug(),
            'geo_location' => [fake()->latitude(), fake()->longitude()],
            'address_1' => fake()->streetName(),
            'address_2' => fake()->optional()->streetAddress(),
            'town' => fake()->city(),
            'county' => fake()->word(),
            'postcode' => fake()->postcode(),
            'state_code' => Str::upper(fake()->randomLetter() . fake()->randomLetter()),
            'country_code' => fake()->countryCode(),
        ];
    }
}
