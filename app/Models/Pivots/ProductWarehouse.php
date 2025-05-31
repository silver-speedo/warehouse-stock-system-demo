<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductWarehouse extends Pivot
{
    protected $table = 'product_warehouses';

    protected $casts = [
        'quantity' => 'integer',
        'threshold' => 'integer',
    ];
}
