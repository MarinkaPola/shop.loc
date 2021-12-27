<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Good;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryResourceCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Category::class);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     * @return JsonResponse
     */
    public function store(CategoryRequest $request)

    {
        $category = Category::create($request->validated());
        return $this->created(CategoryResource::make($category));
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category  $category
     * @return JsonResponse
     */
    public function show(Category $category)
    {
        return $this->success(CategoryResource::make($category)->load(['sales', 'areaCategory']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $category->update($request->validated());
        return $this->success(CategoryResource::make($category));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Category $category)
    {
        $category->categoryGoods()->each(function (Good $good){$good->delete();});

            $category->delete();

        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }
}
