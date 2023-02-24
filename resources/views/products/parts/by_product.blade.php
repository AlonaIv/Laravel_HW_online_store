@if($product->available)
    <form action="{{ route('cart.add', $product) }}" method="POST" class="form-inline">
        @csrf
        @if($showQuantity)
            <div class="form-group col-sm-3 mb-2">
                <label for="product_count" class="sr-only">Count: </label>
                <input type="number"
                       name="product_count"
                       class="form-control"
                       id="product_count"
                       min="1"
                       max="{{ $product->quantity }}"
                       value="1"
                >
            </div>
        @endif
        <button type="submit" class="btn btn-primary">{{ $btnText  }}</button>
    </form>
@endif
