<?php

namespace App\Repositories;

use App\Http\Requests\Admin\CreateProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Image;
use App\Models\Product;
use App\Repositories\Contract\ImageRepositoryContract;
use App\Services\FileStorageService;
use Illuminate\Support\Facades\Storage;

class ProductRepository implements Contract\ProductRepositoryContract
{
    public function __construct(protected ImageRepositoryContract $imagesRepository)
    {
    }

    public function create(CreateProductRequest $request): Product|bool
    {
        try {
            \DB::beginTransaction();

            $data = collect($request->validated())->except(['categories'])->toArray();
            $categories = $request->get('categories', []);
            $product = Product::create($data);
            $this->setCategories($product, $categories);
            $this->imagesRepository->attach(
                $product,
                'images',
                $data['images'] ?? [],
                $product->slug
            );

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
        try {
            \DB::beginTransaction();

            $data = collect($request->validated())->except(['categories'])->toArray();
            $categories = $request->get('categories', []);
            $product->update($data);
            $product->categories()->sync($categories);
            $directory = str_replace('public/', '', pathinfo($product['thumbnail'], PATHINFO_DIRNAME));
            $this->imagesRepository->update($product, 'images', $request->file('images'), $directory);

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
