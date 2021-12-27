<?php

namespace App\Http\Controllers;


use App\Models\Category;
use App\Models\Good;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\GoodRequest;
use App\Http\Resources\GoodResource;
use App\Http\Resources\GoodResourceCollection;


class GoodController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Good::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $sortBy = request()->input('sortBy');
        $sortOrder = request()->input('sortOrder'); //ASC, DESC
        $goodsQuery = Good::query(); //query()     возможность добавлять квери параметры в запрос, по которым в базе делать отбор для формирования коллекции
        if (request()->filled('search')) {
            $search = '%' . request()->input('search') . '%';
            $goodsQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', $search)
                    ->orWhere('feature', 'like', $search);
            });
        }
        if ($sortBy) {
            $goodsQuery->orderBy($sortBy, $sortOrder);
        }
        if (request()->filled('brand')) {
            $goodsQuery->where('brand', request()->brand);
        }
        if (request()->filled('category_id')) {
            $goods = $goodsQuery->where('category_id', request()->category_id)->with(['sales',
                'category.sales', 'category.areaCategory.sales'])->paginate(16, ['*'], 'current_page');
        }
        else if(request()->filled('area_id')) {
            $categories_id = Category::where('area_id', request()->area_id)->get('id');
                $goods=$goodsQuery->whereIn('category_id', $categories_id)->with(['sales', 'category.sales', 'category.areaCategory.sales'])->paginate(16, ['*'], 'current_page');
            }

         else{
             $goods = $goodsQuery->with(['sales', 'category.sales', 'category.areaCategory.sales'])->paginate(16, ['*'], 'current_page');
         }
        return $this->success(GoodResourceCollection::make($goods));
    }


    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index_brand(){
        $brands = Good::all()->pluck('brand')->unique();
        return $this->success($brands);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param GoodRequest $request
     * @return JsonResponse
     */
    public function store(GoodRequest $request)

    {
        $good = Good::create($request->validated());
        return $this->created(GoodResource::make($good));
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Good $good
     * @return JsonResponse
     */
    public function show(Good $good)
    {
        $sales_good = $good->sales->pluck('value_percentage');
        $sales_category = $good->category->sales->pluck('value_percentage');
        $sales_area = $good->category->areaCategory->sales->pluck('value_percentage');
        $masiv_sales = $sales_good->concat($sales_category)->concat($sales_area);
        $max_sale = $masiv_sales->max();
        $good['max_sale'] = $max_sale;
        return $this->success(GoodResource::make($good)->load(['sales', 'category',
            'category.sales', 'category.areaCategory.sales', 'reviews', 'reviews.author']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Good $good
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(GoodRequest $request, Good $good)
    {

        $good->update($request->validated());
        return $this->success(GoodResource::make($good));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Good $good
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Good $good)
    {
        $good->delete();
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }


}


