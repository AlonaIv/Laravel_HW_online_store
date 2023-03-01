<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateProductRequest;
use App\Http\Requests\Admin\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\Contract\ProductRepositoryContract;
use App\Services\FileStorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $products = Product::with('categories')->withCount('followers')->sortable()->paginate(5);

        return view('admin/products/index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin/products/create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateProductRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CreateProductRequest $request, ProductRepositoryContract $repository)
    {
        if ($repository->create($request)) {
            return redirect()->route('admin.products.index');
        } else {
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Product $product)
    {
        $product = $product->load('categories');
        $productCategories = $product->categories()->get()->pluck('id')->toArray();

        $this->middleware('permission:' . config('permission.access.products.edit'));

        return view('admin/products/edit', ['categories' => Category::all(), 'product' => $product, 'product_categories' => $productCategories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateProductRequest $request, ProductRepositoryContract $repository, Product $product)
    {
        if ($repository->update($request, $product)) {
            $product = $product->load('categories');
            return redirect()->route('admin.products.edit', ['categories' => Category::all(), 'product' => $product, 'product_categories' => $product->categories]);
        } else {
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        $this->middleware('permission:' . config('permission.access.products.delete'));

        $dirname = pathinfo($product['thumbnail'], PATHINFO_DIRNAME);
        FileStorageService::removeDirectory($dirname);
        $product->deleteOrFail();

        return redirect()->route('admin.products.index');
    }
}
