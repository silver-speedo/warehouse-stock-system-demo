<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\WarehouseController;
use App\Http\Controllers\Api\OrderController;

Route::get('/products', [ProductController::class, 'list']);
Route::get('/products/{uuid}', [ProductController::class, 'view']);

Route::get('/warehouses', [WarehouseController::class, 'list']);

Route::get('/orders', [OrderController::class, 'list']);
Route::post('/orders', [OrderController::class, 'create']);
