<?php

namespace Modules\Order\Http\Controllers\Api;

use App\Enums\HttpStatus;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Modules\Order\Http\Requests\StoreOrderRequest;
use Modules\Order\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService) {
        $this->orderService = $orderService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        try {
            $order = $this->orderService->placeOrder(
                $request->validated(),
                auth()->id()
            );

            return ApiResponse::success([
                'message' => __('order::messages.order_success'),
                'order_id' => $order->id
            ], HttpStatus::CREATED->value);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
