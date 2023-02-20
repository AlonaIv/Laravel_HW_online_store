@extends('layouts.admin')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <br>
                <h3 class="text-center">{{ __('Edit product') }}</h3>
                <hr>
            </div>
            <div class="col-md-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
            <div class="col-md-12">
                <form action="{{ route('admin.products.update', $product) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-group row">
                        <label for="title" class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                        <div class="col-md-6">
                            <input id="title"
                                   type="text"
                                   class="form-control @error('title') is-invalid @enderror"
                                   name="title"
                                   value="{{ $product->title }}"
                                   autocomplete="title"
                                   autofocus
                            >
                        </div>
                    </div>
                    <div class="form-group row pt-2">
                        <label for="categories"
                               class="col-md-4 col-form-label text-md-right">{{ __('Categories') }}</label>
                        <div class="col-md-6">
                            <select id="categories"
                                    class="form-control @error('categories') is-invalid @enderror"
                                    name="categories[]" multiple
                            >
                                @foreach($categories as $category)
                                    <option value="{{ $category['id'] }}"
                                            @if(in_array($category['id'], $product_categories)) selected @endif
                                        {{--                                    @foreach($product_categories as $prCategory)--}}
                                        {{--                                        {{ $category['id'] === $prCategory->id ? 'selected' : '' }}--}}
                                        {{--                                        @endforeach--}}
                                    >{{ $category['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row pt-2">
                        <label for="SKU" class="col-md-4 col-form-label text-md-right">{{ __('SKU') }}</label>
                        <div class="col-md-6">
                            <input id="SKU"
                                   type="text"
                                   class="form-control @error('SKU') is-invalid @enderror"
                                   name="SKU"
                                   value="{{ $product->SKU }}"
                                   autocomplete="SKU"
                                   autofocus
                            >
                        </div>
                    </div>
                    <div class="form-group row pt-2">
                        <label for="price" class="col-md-4 col-form-label text-md-right">{{ __('Price') }}</label>
                        <div class="col-md-6">
                            <input id="price"
                                   type="text"
                                   class="form-control @error('price') is-invalid @enderror"
                                   name="price"
                                   value="{{ $product->price }}"
                                   autocomplete="price"
                                   autofocus
                            >
                        </div>
                    </div>
                    <div class="form-group row pt-2">
                        <label for="discount" class="col-md-4 col-form-label text-md-right">{{ __('Discount') }}</label>
                        <div class="col-md-6">
                            <input id="discount"
                                   type="text"
                                   class="form-control @error('discount') is-invalid @enderror"
                                   name="discount"
                                   value="{{ $product->discount }}"
                                   autocomplete="discount"
                                   autofocus
                            >
                        </div>
                    </div>
                    <div class="form-group row pt-2">
                        <label for="quantity"
                               class="col-md-4 col-form-label text-md-right">{{ __('Quantity') }}</label>
                        <div class="col-md-6">
                            <input id="quantity"
                                   type="number"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   name="quantity"
                                   value="{{ $product->quantity }}"
                                   autocomplete="quantity"
                                   autofocus
                            >
                        </div>
                    </div>
                    <div class="form-group row pt-2">
                        <label for="description"
                               class="col-md-4 col-form-label text-md-right">{{ __('Description') }}</label>
                        <div class="col-md-6">
                            <textarea name="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      cols="30"
                                      rows="10">{{ $product->description }}</textarea>
                        </div>
                    </div>
                    <div class="form-group row pt-2">
                        <label for="thumbnail"
                               class="col-md-4 col-form-label text-md-right">{{ __('Thumbnail') }}</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <img src="{{ $product->thumbnailUrl  }}" style="height: 200px"
                                         id="thumbnail-preview" alt="">
                                </div>
                                <div class="col-md-6">
                                    <input type="file" name="thumbnail" id="thumbnail">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row pt-2">
                        <label for="images" class="col-md-4 col-form-label text-md-right">{{ __('Images') }}</label>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row images-wrapper">
                                        @foreach($product->images as $image)
                                            @if(Storage::has($image->path))
                                                <div class="col-sm-12 d-flex justify-content-center align-items-center">
                                                    <img src="{{ $image->url }}" class="card-img-top"
                                                         style="max-width: 200px; margin: 0 auto; display: block;">
                                                    <a data-route="{{ route('ajax.images.delete', $image) }}"
                                                       class="btn btn-danger remove-product-image">x</a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <input type="file" name="images[]" id="images" multiple>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row pt-2">
                        <div class="col-md-10 text-right">
                            <input type="submit" class="btn btn-info" value="Update">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('footer-scripts')
    <script src="{{ mix('js/images-preview.js') }}"></script>
    {{--    @vite(['resources/js/images-preview.js', 'resources/js/images-actions.js'])--}}
@endpush
