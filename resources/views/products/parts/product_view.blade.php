<div class="col-md-4">
    <div class="card mb-4 shadow-sm">
        <img src="{{ $product->thumbnailUrl }}" height="300" class="card-img-top"
             style="max-width: 100%; margin: 0 auto; display: block;">
        <div class="card-body">
            <p class="card-title">{{ __($product->title) }}</p>
            <hr>
            <div class="d-flex flex-column justify-content-center align-items-start">
                <div class="btn-group d-flex justify-content-between w-100 align-items-center">
                    <small class="text-muted">Categories: </small>
                    @each('categories.parts.category_view', $product->categories, 'category')
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between align-items-center">
                <div class="btn-group">
                    <a href="{{ route('products.show', $product) }}"
                       class="btn btn-sm btn-outline-dark">
                        {{ __('Show') }}
                    </a>
                    @include('products.parts.by_product', ['product'=>$product, 'showQuantity'=>false, 'btnText'=>'Buy'])
                </div>
                <div class="text-muted">
                    @if ($product->price !== $product->endPrice)
                        <span class="text-muted old-price">{{ $product->price }}$</span>
                    @endif
                    <span class="text-muted">{{ $product->endPrice }}$</span>
                </div>
            </div>
        </div>
    </div>
</div>
