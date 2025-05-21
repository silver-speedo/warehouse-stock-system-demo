<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Jobs\CreateOrderJob;
use App\Repositories\Contracts\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    private OrderRepositoryInterface $orders;

    public function __construct(OrderRepositoryInterface $orders)
    {
        $this->orders = $orders;
    }

    public function list(): JsonResponse
    {
        return response()->json($this->orders->all());
    }

    public function create(CreateOrderRequest $request): JsonResponse
    {
        CreateOrderJob::dispatchSync($request->input('product_uuid'), $request->input('quantity'));

        return response()->json([
            'message' => 'Order placed successfully!',
        ], 201);
    }
}
