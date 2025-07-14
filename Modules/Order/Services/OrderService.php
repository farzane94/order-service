<?php

namespace Modules\Order\Services;

use Illuminate\Support\Facades\DB;
use Modules\Order\Repositories\OrderRepository;
use Modules\Order\Models\Order;
use Modules\Product\Repositories\ProductRepository;


class OrderService
{
    public function __construct(
        protected OrderRepository   $orderRepository,
        protected ProductRepository $productRepository
    )
    {
    }

    public function placeOrder(array $data, int $userId): Order
    {
        return DB::transaction(function () use ($data, $userId) {
            $items = [];
            $total = 0;

            foreach ($data['products'] as $item) {
                $product = $this->productRepository->findByIdForUpdate($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception(__('order::messages.insufficient_stock', ['product' => $product->title]));
                }

                $this->productRepository->decreaseStock($product, $item['quantity']);

                $total += $product->price * $item['quantity'];

                $items[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            $total += Order::SHIPPINGCOSTS[$data['shipping_method']];

            $order = $this->orderRepository->create([
                'user_id' => $userId,
                'status' => Order::STATUS_PAID,
                'shipping_method' => $data['shipping_method'],
                'shipping_cost' => Order::SHIPPINGCOSTS[$data['shipping_method']],
                'total_price' => $total,
            ]);

            $this->orderRepository->addItems($order, $items);

            return $order;
        });
    }
}
