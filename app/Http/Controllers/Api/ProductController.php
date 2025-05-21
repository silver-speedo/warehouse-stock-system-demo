<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\StockRepositoryInterface;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    private ProductRepositoryInterface $products;

    private StockRepositoryInterface $stocks;

    public function __construct(ProductRepositoryInterface $products, StockRepositoryInterface $stocks)
    {
        $this->products = $products;
        $this->stocks = $stocks;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->products->all()->pluck('title', 'uuid'));
    }

    public function list(): JsonResponse
    {
        $paginated = $this->products->paginated();

        $result = $paginated->getCollection()->map(function ($product) {
            $uuid = $product->uuid;

            return [
                'uuid' => $uuid,
                'title' => $product->title,
                'price' => $product->price,
                'total_quantity' => $this->stocks->getTotalQuantity($uuid),
                'allocated_to_orders' => $this->stocks->getAllocatedQuantity($uuid),
                'physical_quantity' => $this->stocks->getPhysicalQuantity($uuid),
                'total_threshold' => $this->stocks->getTotalThreshold($uuid),
                'immediate_dispatch' => $this->stocks->getImmediateDispatchQuantity($uuid),
                'warehouses' => $this->stocks->getWarehouseBreakdown($uuid),
            ];
        });

        $paginated->setCollection($result);

        return response()->json($paginated);
    }

    public function view(string $uuid): JsonResponse
    {
        $product = $this->products->find($uuid);

        return response()->json([
            'product' => $product,
            'allocated' => $this->stocks->getAllocatedQuantity($uuid),
            'threshold' => $this->stocks->getTotalThreshold($uuid),
            'physical' => $this->stocks->getPhysicalQuantity($uuid),
            'dispatchable' => $this->stocks->getImmediateDispatchQuantity($uuid),
        ]);
    }
}
