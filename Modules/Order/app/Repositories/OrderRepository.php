<?php

namespace Modules\Order\Repositories;

use Modules\Order\Models\Order;
use Modules\Order\Models\OrderItem;

class OrderRepository
{
    public function handle() {}

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function addItems(Order $order, array $items): void
    {
        foreach ($items as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                ...$item
            ]);
        }
    }
}
