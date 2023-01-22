@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header">{{ __('Dashboard') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-md-12">
                            <div class="row">
                                @each('categories.parts.category_view', $categories, 'category')
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="row">
                                @each('products.parts.product_view', $products, 'product')
                            </div>
                        </div>

                        {{--                        <br>--}}
                        {{--                        <div class="col-md-12">--}}
                        {{--                            <div class="row">--}}
                        {{--                                @each('categories.parts.category_view', $categories, 'category')--}}
                        {{--                            </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
