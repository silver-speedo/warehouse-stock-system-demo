<?php

namespace App\Providers;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\StockRepositoryInterface;
use App\Repositories\Contracts\WarehouseRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, config('repositories.product'));
        $this->app->bind(WarehouseRepositoryInterface::class, config('repositories.warehouse'));
        $this->app->bind(StockRepositoryInterface::class, config('repositories.stock'));
        $this->app->bind(OrderRepositoryInterface::class, config('repositories.order'));
    }
}
