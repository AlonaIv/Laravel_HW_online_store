@extends('layouts.app')

@section('content')
    <div class="col-12 text-center">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Thank you {{ $order->user->fullName }}!</h3>
                <h4 class="card-subtitle mb-2 text-muted">Currently your order in process</h4>
                <h4 class="card-subtitle mb-2 text-muted">Order total: <strong>{{ $order->total }}$</strong></h4>
                <hr>
                <table class="table table-light">
                    <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($order->products as $product)
                        <tr>
                            <td><img src="{{ $product->thumbnailUrl }}" width="75" alt=""></td>
                            <td><a href="{{ route('products.show', $product) }}">{{ $product->title }}</a></td>
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>{{ $product->pivot->single_price }}</td>
                            <td>{{ $product->pivot->single_price * $product->pivot->quantity }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{--                <a href="{{ route('orders.generate.invoice', $order) }}" class="btn btn-outline-primary">Download Invoice</a>--}}
                {{--                <a href="{{ route('account.orders.show', $order) }}"--}}
                {{--                   class="btn btn-outline-primary">Order details</a>--}}
            </div>
        </div>
    </div>
@endsection
