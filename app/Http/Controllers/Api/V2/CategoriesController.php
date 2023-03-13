<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Categories\V2\CategoriesResource;
use App\Http\Resources\Categories\V2\SingleCategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return CategoriesResource
     */
    public function index()
    {
        $categories = Category::paginate(5);

        return new CategoriesResource($categories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return SingleCategoryResource
     */
    public function show($id)
    {
        if (!$this->userCan('read')) {
            return $this->notAllowedResponse();
        }
//        dd(Category::where('id', $id)->first());
        $category = Category::where('id', $id)->first();

        return new SingleCategoryResource($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
