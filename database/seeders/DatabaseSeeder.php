<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Warehouse::factory(50)->create();

        Product::factory(2000)->create()->each(function (Product $product) {
            $warehouse = Warehouse::query()->inRandomOrder()->first()->uuid;
            $quantity = rand(0, 20);
            $threshold = rand(0, $quantity);

            $product->warehouses()->syncWithPivotValues(
                $warehouse,
                [
                    'quantity' => $quantity,
                    'threshold' => $threshold,
                ]
            );

            if (rand(0, 1) === 1) {
                $warehouse = Warehouse::query()->whereNot('uuid', $warehouse)->inRandomOrder()->first()->uuid;
                $quantity = rand(0, 20);
                $threshold = rand(0, $quantity);

                $product->warehouses()->syncWithPivotValues(
                    $warehouse,
                    [
                        'quantity' => $quantity,
                        'threshold' => $threshold,
                    ],
                    false
                );
            }
        });

        Order::factory(200)->create()->each(function (Order $order) {
            $quantity = rand(1, 10);
            $product = Product::query()->inRandomOrder()->first();

            $order->products()->syncWithPivotValues($product->uuid, [
                'warehouse_uuid' => Warehouse::query()->inRandomOrder()->first()->uuid,
                'quantity' => $quantity,
                'price' => $product->price,
                'total' => $quantity * $product->price,
            ]);

            if (rand(0, 1) === 1) {
                $quantity = rand(1, 10);
                $product = Product::query()->whereNot('uuid', $product->id)->inRandomOrder()->first();

                $order->products()->syncWithPivotValues($product->uuid, [
                    'warehouse_uuid' => Warehouse::query()->inRandomOrder()->first()->uuid,
                    'quantity' => $quantity,
                    'price' => $product->price,
                    'total' => $quantity * $product->price,
                ]);
            }
        });
    }
}
