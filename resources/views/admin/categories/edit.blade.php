@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit Category') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.categories.update', $category) }}">
                            @csrf
                            @method('PUT')

                            @if ($categories)
                                <div class="row mb-3">
                                    <label for="parent"
                                           class="col-md-4 col-form-label text-md-end">{{ __('Parent Category') }}</label>

                                    <div class="col-md-6">
                                        <select name="parent_id" id="parent" class="form-control @error('parent_id') is-invalid @enderror">
                                            <option value=""></option>
                                            @foreach($categories as $pCategory)
                                                <option value="{{$pCategory->id}}"
                                                        @if($pCategory->id === $category->parent_id) selected @endif
                                                >{{$pCategory->name}}</option>
                                            @endforeach
                                        </select>

                                        @error('parent_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            @endif

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           class="form-control @error('name') is-invalid @enderror" name="name"
                                           value="{{ old('name') ?? $category->name }}" required>
                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="description"
                                       class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>
                                <div class="col-md-6">
<textarea name="description"
          class="form-control @error('description') is-invalid @enderror"
          id="description" cols="30" rows="10">{{ old('description') ?? $category->description }}</textarea>                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
