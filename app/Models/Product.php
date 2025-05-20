<?php

namespace App\Models;

use App\Models\Pivots\OrderProduct;
use App\Models\Pivots\ProductWarehouse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasUuids;
    use HasFactory;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'title',
        'description',
        'price',
    ];

    protected $casts = [
        'price' => 'double',
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_products', 'product_uuid', 'order_uuid')
            ->using(OrderProduct::class)
            ->withPivot(['quantity', 'price', 'total']);
    }

    public function warehouses(): BelongsToMany
    {
        return $this->belongsToMany(Warehouse::class, 'product_warehouses', 'product_uuid', 'warehouse_uuid')
            ->using(ProductWarehouse::class)
            ->withPivot(['quantity', 'threshold']);
    }
}
