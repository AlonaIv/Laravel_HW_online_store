<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('cart/index');
    }

    public function add(Request $request, Product $product)
    {
        Cart::instance('cart')->add(
            $product->id,
            $product->title,
            $request->get('product_count', 1),
            $product->endPrice,
        )->associate(Product::class);

        notify()->success('Product was added to the cart :)', position: 'topRight');

        return redirect()->back();
    }

    public function remove(Request $request)
    {
        $rowId = $this->getRowId($request);

        Cart::instance('cart')->remove($rowId);
        notify()->success('Product was removed from the cart :)', position: 'topRight');

        return redirect()->back();
    }

    public function countUpdate(Request $request, Product $product)
    {
        $requestedCount = $request->get('product_count');

        if ($requestedCount > $product->quantity) {
            notify()->error('Max count of current product is {$product->quantity}', position: 'topRight');

            return redirect()->back();
        }

        $rowId = $this->getRowId($request);

        Cart::instance('cart')->update($rowId, $requestedCount);
        notify()->success('Product count was updated :)', position: 'topRight');

        return redirect()->back();
    }

    protected function getRowId(Request $request)
    {
        $rowId = $request->get('rowId');

        if (!$rowId) {
            notify()->error('Something went wrong :(', position: 'topRight');
            logs()->warning('Removing without row id');

            return redirect()->back();
        }

        return $rowId;
    }
}
