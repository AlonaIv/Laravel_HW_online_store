<?php

namespace App\Repositories;

use App\Helpers\TransactionAdapter;
use App\Models\Order;
use App\Models\OrderStatus;
use Gloudemans\Shoppingcart\Facades\Cart;


class OrderRepository implements Contract\OrderRepositoryContract
{
    const ORDER_STATUSES = ['completed' => 'COMPLETED'];

    public function create(array $request): Order|bool
    {
        $status = OrderStatus::default()->first();
        $request = array_merge($request, ['status_id' => $status->id]);
        $order = auth()->user()->orders()->create($request);

        $this->addProductToOrder($order);

        return $order;
    }

    public function setTransaction(string $vendorOrderId, TransactionAdapter $adapter): Order
    {
        $order = Order::where('vendor_order_id', $vendorOrderId)->firstOrFail();
        $order->transaction()->create((array)$adapter);

        if ($adapter->status === self::ORDER_STATUSES['completed']) {
            $order->update([
                'status_id' => OrderStatus::paid()->firstOrFail()->id,
            ]);
        }

        return $order;
    }


    protected function addProductToOrder(Order $order)
    {
        Cart::instance('cart')->content()->groupBy('id')->each(function ($item) use ($order) {
            $cartItem = $item->first();
            $order->products()->attach(
                $cartItem->model,
                [
                    'quantity' => $cartItem->qty,
                    'single_price' => $cartItem->price
                ]
            );

            $quantity = $cartItem->model->quantity - $cartItem->qty;

            if (!$cartItem->model->update(compact('quantity'))) {
                throw new \Exception("Something went wrong with product (id - {$cartItem->model->id}) quantity update");
            }
        });
    }
}
