<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;

class RemoveImageController extends Controller
{
    public function __invoke(Image $image)
    {
        try {
            $image->delete();

            return response()->json('Image was removed.');

        } catch (\Exception $e) {
            logs()->error($e);

            return response(status: 422)->json(['massage' => $e->getMessage()]);
        }
    }
}
