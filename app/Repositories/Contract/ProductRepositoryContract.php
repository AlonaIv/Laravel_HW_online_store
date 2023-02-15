<?php

namespace App\Repositories\Contract;

use App\Http\Requests\Admin\CreateProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Product;

interface ProductRepositoryContract
{
    public function create(CreateProductRequest $request): Product|bool;

    public function update(UpdateProductRequest $request, Product $product): Product|bool;

    public function setCategories(Product $product, array $categories = []): void;
}
