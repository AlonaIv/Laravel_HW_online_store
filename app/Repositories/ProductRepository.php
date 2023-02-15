<?php

namespace App\Repositories;

use App\Http\Requests\Admin\CreateProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;

class ProductRepository implements Contract\ProductRepositoryContract
{
    public function create(CreateProductRequest $request): Product|bool
    {
        try {
            \DB::beginTransaction();

            $data = collect($request->validated())->except(['categories'])->toArray();
            $categories = $request->get('categories', []);
            $product = Product::create($data);
            $this->setCategories($product, $categories);

            \DB::commit();

            return $product;

        } catch (\Exception $exception) {
            \DB::rollBack();
            logs()->warning($exception);

            return false;
        }
    }

    public function update(UpdateProductRequest $request, Product $product): Product|bool
    {
        //TODO: update categories. something with image and with table products_categories
        try {
            \DB::beginTransaction();

            $data = collect($request->validated())->except(['categories'])->toArray();
            $categories = $request->get('categories', []);
            $product->update($data);
            $product->categories()->sync($categories);

            \DB::commit();

            return $product;

        } catch (\Exception $exception) {
            \DB::rollBack();
            logs()->warning($exception);

            return false;
        }
    }


    public function setCategories(Product $product, array $categories = []): void
    {
        if (!empty($categories)) {
            $product->categories()->attach($categories);
        }
    }
}
