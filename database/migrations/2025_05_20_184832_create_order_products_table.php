<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->uuid('order_uuid');
            $table->uuid('product_uuid');
            $table->uuid('warehouse_uuid');
            $table->decimal('price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('total', 10, 2);

            $table->unique(['order_uuid', 'product_uuid']);
            $table->foreign('order_uuid')->references('uuid')->on('orders')->onDelete('cascade');
            $table->foreign('product_uuid')->references('uuid')->on('products')->onDelete('cascade');
            $table->foreign('warehouse_uuid')->references('uuid')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_products');
    }
};
