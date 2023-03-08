<?php

namespace App\Services;

use App\Events\OrderCreated;
use App\Helpers\TransactionAdapter;
use App\Http\Requests\CreateOrderRequest;
use App\Repositories\Contract\OrderRepositoryContract;
use Gloudemans\Shoppingcart\Facades\Cart;
use Srmklive\PayPal\Services\PayPal;

class PaypalService implements Contracts\PaypalServiceContract
{
    const PAYMENT_SYSTEM = 'PAYPAL';

    protected PayPal $payPalClient;

    public function __construct()
    {
        $this->payPalClient = new PayPal();
        $this->payPalClient->setApiCredentials(config('paypal'));
        $this->payPalClient->setAccessToken($this->payPalClient->getAccessToken());
    }

    public function create(CreateOrderRequest $request, OrderRepositoryContract $repository)
    {
        try {
            \DB::beginTransaction();

            $total = Cart::instance('cart')->total();
            $paypalOrder = $this->createPaymentOrder($total);
            $request = array_merge(
                $request->validated(),
                [
                    "vendor_order_id" => $paypalOrder['id'],
                    "total" => $total
                ]
            );
            $order = $repository->create($request);

            \DB::commit();
//dd(json_encode($order->toArray()));
            return response()->json($order);
        } catch (\Exception $exception) {
            \DB::rollBack();

            return $this->errorHandler($exception);
        }
    }

    public function capture(string $vendorOrderId, OrderRepositoryContract $repository)
    {
        try {
            \DB::beginTransaction();

            $result = $this->payPalClient->capturePaymentOrder($vendorOrderId);
            $order = $repository->setTransaction($vendorOrderId, new TransactionAdapter(
                self::PAYMENT_SYSTEM,
                auth()->id(),
                $result['status']
            ));
            $result['orderId'] = $order->id;

            \DB::commit();

            OrderCreated::dispatch($order);

            return response()->json($result);
        } catch (\Exception $exception) {
            \DB::rollBack();

            return $this->errorHandler($exception);
        }
    }

    protected function createPaymentOrder($total)
    {
        return $this->payPalClient->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => config('paypal.currency'),
                        'value' => $total
                    ]
                ]
            ]
        ]);
    }

    protected function errorHandler(\Exception $exception)
    {
        logs()->warning($exception);

        return response()->json(['error' => $exception->getMessage()], 422);
    }
}
