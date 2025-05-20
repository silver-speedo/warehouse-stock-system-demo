<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Models\Pivots\OrderProduct;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasUuids;
    use HasFactory;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'order_status',
        'total',
    ];

    protected $casts = [
        'order_status' => OrderStatus::class,
        'total' => 'double',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_products', 'order_uuid', 'product_uuid')
            ->using(OrderProduct::class)
            ->withPivot(['quantity', 'price', 'total']);
    }
}
