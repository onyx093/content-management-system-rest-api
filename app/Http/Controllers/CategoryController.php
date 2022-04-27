<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\{StoreCategoryRequest, UpdateCategoryRequest};
use App\Http\Resources\{CategoryCollection, CategoryResource};
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::hasUser()){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category = Category::paginate();

        return new CategoryCollection($category);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCategoryRequest $request)
    {
        if(!Auth::hasUser()){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category = new Category();
        $category->name = $request->input('name');
        $category->save();

        return response()->json(new CategoryResource($category), 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        if(!Auth::hasUser()){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(new CategoryResource($category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCategoryRequest  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if(!Auth::hasUser()){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category->name = $request->input('name');
        $category->save();

        return response()->json(new CategoryResource($category));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if(!Auth::hasUser()){
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $category->delete();

        return response()->json(null, 204);
    }
}
