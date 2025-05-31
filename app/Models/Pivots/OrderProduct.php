<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    protected $table = 'order_products';

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'double',
        'total' => 'double',
    ];
}
