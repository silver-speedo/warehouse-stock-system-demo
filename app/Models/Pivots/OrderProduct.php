<?php

namespace App\Models\Pivots;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class OrderProduct extends Pivot
{
    use HasFactory;

    protected $table = 'order_products';

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'double',
        'total' => 'double',
    ];
}
