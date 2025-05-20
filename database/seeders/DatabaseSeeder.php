<?php

namespace Database\Seeders;


use App\Models\Order;
use App\Models\Pivots\OrderProduct;
use App\Models\Pivots\ProductWarehouse;
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
        Warehouse::factory(5)->create();

        Product::factory(50)->create()->each(function (Product $product) {
            $product->warehouses()->syncWithPivotValues(
                Warehouse::query()->inRandomOrder()->first()->uuid,
                [
                    'quantity' => rand(0, 20),
                    'threshold' => rand(1, 10),
                ]
            );
        });

        Order::factory(20)->create()->each(function (Order $order) {
            $quantity = rand(1, 10);
            $product = Product::query()->inRandomOrder()->first();

            $order->products()->syncWithPivotValues($product->uuid, [
                'quantity' => $quantity,
                'price' => $product->price,
                'total' => $quantity * $product->price,
            ]);
        });
    }
}
