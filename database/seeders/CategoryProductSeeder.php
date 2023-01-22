<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::factory(5)->create()->each(function($category) {
            $this->addProductsToCategory($category);
            if (rand(0, 1)) {
                $subCategories = Category::factory(rand(1, 3))->make();
                $category->childs()->saveMany($subCategories);

                $subCategories->each(function ($subCategory) {
                    $this->addProductsToCategory($subCategory);
                });
            }
        });
    }

    protected function addProductsToCategory($category)
    {
        $products = Product::factory(rand(2, 5))->create()->pluck('id');
        $category->products()->attach($products);
    }
}
