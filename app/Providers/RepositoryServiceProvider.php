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
        $this->app->singleton(ProductRepositoryInterface::class, config('repositories.product'));
        $this->app->singleton(WarehouseRepositoryInterface::class, config('repositories.warehouse'));
        $this->app->singleton(StockRepositoryInterface::class, config('repositories.stock'));
        $this->app->singleton(OrderRepositoryInterface::class, config('repositories.order'));
    }
}
