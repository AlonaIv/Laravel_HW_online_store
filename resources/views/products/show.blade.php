@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h3 class="text-center">{{ __($product->title) }}</h3>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6">
            <img src="{{ $product->thumbnailUrl }}" class="card-img-top"
                 style="width: 400px; height: 300px; margin: 0 auto; display: block;">
        </div>
        <div class="col-md-6">
            @if ($product->price !== $product->end_price)
                <p>Old Price: <span class="old-price">{{ $product->price }}$</span></p>
            @endif
            <p>Price: {{ $product->end_price }}$</p>
            <p>SKU: {{ $product->SKU }}</p>
            <p>In stock: {{ $product->quantity }}</p>
            <p>Rating: {{ round($product->averageRating(), 2) }}</p>
            <hr>
            <div>
                <p>Product Category:
                @each('categories.parts.category_view', $product->categories, 'category')
            </div>
            <hr>
            <div>
                <p>Add to Cart: </p>
                @include('products.parts.by_product', ['product'=>$product, 'showQuantity'=>true, 'btnText'=>'Buy'])
            </div>
            @auth
                <form class="form-horizontal poststars" action="{{ route('products.rate', $product) }}"
                      id="addStar"
                      method="POST">
                    @csrf
                    <div class="form-group required">
                        <div class="col-sm-3 stars">
                            @if(!is_null($product->user_rate))
                                @for($i = 1; $i <= 5; $i++)
                                    <input class="star star-{{$i}}"
                                           value="{{$i}}"
                                           id="star-{{$i}}"
                                           type="radio"
                                           name="star"
                                        {{ $i == $product->user_rate?->rating ? 'checked' : '' }}
                                    />
                                    <label class="star star-{{$i}}" for="star-{{$i}}"></label>
                                @endfor
                            @else
                                <input class="star star-1" value="1" id="star-1" type="radio" name="star"/>
                                <label class="star star-1" for="star-1"></label>
                                <input class="star star-2" value="2" id="star-2" type="radio" name="star"/>
                                <label class="star star-2" for="star-2"></label>
                                <input class="star star-3" value="3" id="star-3" type="radio" name="star"/>
                                <label class="star star-3" for="star-3"></label>
                                <input class="star star-4" value="4" id="star-4" type="radio" name="star"/>
                                <label class="star star-4" for="star-4"></label>
                                <input class="star star-5" value="5" id="star-5" type="radio" name="star"/>
                                <label class="star star-5" for="star-5"></label>
                            @endif
                        </div>
                    </div>
                </form>
                <hr>
                @if(auth()->user()->isWishedProduct($product))
                    <form action="{{ route('wishlist.remove', $product) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="submit" class="btn btn-outline-danger" value="Remove from Wish List">
                    </form>
                @else
                    <form action="{{ route('wishlist.add', $product) }}" method="POST">
                        @csrf
                        <input type="submit" class="btn btn-outline-success" value="'Add to Wish List">
                    </form>
                @endif
            @endauth
        </div>
    </div>
    <hr>
    <div class="row-fluid">
        <div class="col-md-10 text-center">
            <h4>Description: </h4>
            <p>{{ $product->description }}</p>
        </div>
    </div>
    <hr>
    {{--    <div class="container">--}}
    {{--        <div class="row">--}}
    {{--            <div class="col-12 text-center">--}}
    {{--                <h4>Comments</h4>--}}
    {{--            </div>--}}
    {{--            <br>--}}
    {{--            <div class="row">--}}
    {{--                @foreach($comments as $comment)--}}
    {{--                    @include('comments/_single_comment', ['comment' => $comment, 'model' => $product])--}}
    {{--                @endforeach--}}
    {{--            </div>--}}
    {{--            <div class="row">--}}
    {{--                {{ $comments->links() }}--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <div class="row">--}}
    {{--            <div class="col-12 d-flex flex-column justify-content-center align-items-center">--}}
    {{--                <br>--}}
    {{--                @include('comments/_form', ['model' => $product])--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
@endsection
@push('footer-scripts')
    @vite(['resources/js/product-actions.js'])
@endpush
