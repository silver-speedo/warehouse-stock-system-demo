<?php

namespace App\Models;

use App\Models\Pivots\ProductWarehouse;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Warehouse extends Model
{
    use HasUuids;
    use HasFactory;

    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'slug',
        'geo_location',
        'address_1',
        'address_2',
        'town',
        'county',
        'postcode',
        'state_code',
        'country_code',
    ];

    protected $casts = [
        'geo_location' => 'array',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_warehouses', 'warehouse_uuid', 'product_uuid')
            ->using(ProductWarehouse::class)
            ->withPivot(['quantity', 'threshold']);
    }
}
