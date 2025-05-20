<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductWarehouse extends Pivot
{
    use HasFactory;

    protected $table = 'product_warehouses';

    protected $casts = [
        'quantity' => 'integer',
        'threshold' => 'integer',
    ];
}
