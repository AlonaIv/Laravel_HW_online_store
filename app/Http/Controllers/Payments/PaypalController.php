<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
use App\Services\Contracts\PaypalServiceContract;
use Gloudemans\Shoppingcart\Facades\Cart;


class PaypalController extends Controller
{
    public function create(CreateOrderRequest $request, PaypalServiceContract $paypal)
    {
        return app()->call([$paypal, 'create'], compact('request'));
    }

    public function capture(string $vendorOrderId, PaypalServiceContract $paypal)
    {
        return app()->call([$paypal, 'capture'], compact('vendorOrderId'));
    }

    public function thankYou(string $vendorOrderId)
    {
        Cart::instance('cart')->destroy();

        $order = Order::with(['user', 'transaction', 'products'])->where('vendor_order_id', $vendorOrderId)->firstOrFail();

        return view('thankyou/summary', compact('order'));
    }


}
