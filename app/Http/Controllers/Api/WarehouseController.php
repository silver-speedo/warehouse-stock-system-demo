<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\WarehouseRepositoryInterface;
use Illuminate\Http\JsonResponse;

class WarehouseController extends Controller
{
    private WarehouseRepositoryInterface $warehouses;

    public function __construct(WarehouseRepositoryInterface $warehouses)
    {
        $this->warehouses = $warehouses;
    }

    public function list(): JsonResponse
    {
        return response()->json($this->warehouses->all());
    }
}
