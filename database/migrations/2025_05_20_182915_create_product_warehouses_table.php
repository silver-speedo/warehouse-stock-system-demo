<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_warehouses', function (Blueprint $table) {
            $table->uuid('product_uuid');
            $table->uuid('warehouse_uuid');
            $table->unsignedInteger('quantity')->default(0);
            $table->unsignedInteger('threshold')->default(0);

            $table->unique(['product_uuid', 'warehouse_uuid']);
            $table->foreign('product_uuid')->references('uuid')->on('products')->onDelete('cascade');
            $table->foreign('warehouse_uuid')->references('uuid')->on('warehouses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_warehouses');
    }
};
