<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class WishListController extends Controller
{
    public function __invoke()
    {
        return view('account/wishlist/index');
    }
}
