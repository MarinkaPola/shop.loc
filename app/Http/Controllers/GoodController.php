<?php

namespace App\Http\Controllers;


use App\Models\Good;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
    @return JsonResponse
     */
    public function index()
    {
        $sortBy = request()->input('sortBy');
        $sortOrder = request()->input('sortOrder'); //ASC, DESC
        $goodsQuery = Good::query(); //query()     возможность добавлять квери параметры в запрос, по которым в базе делать отбор для формирования коллекции
        if (request()->filled('search')){
            $search = '%'.request()->input('search').'%';
            $goodsQuery->where(function ($query) use ($search){
                $query->where('title', 'like', $search)
                ->orWhere('feature', 'like', $search );
            });
        }
    if ($sortBy){
    $goodsQuery->orderBy($sortBy, $sortOrder);
    }
    if (request()->filled('brand'))
    {
        $goodsQuery->where('brand', request()->brand);
    }
    if (request()->filled('category_id')) {
           $goods = $goodsQuery->where('category_id',request()->category_id)->with(['sales', 'category.sales', 'category.areaCategory.sales'])->paginate();
    }
   else {
       $goods = $goodsQuery->with(['sales', 'category.sales', 'category.areaCategory.sales'])->paginate();
         }
        return $this->success(GoodResourceCollection::make($goods));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  GoodRequest $request
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
     * @param  \App\Models\Good  $good
     * @return JsonResponse
     */
    public function show(Good $good)
    {
        return $this->success(GoodResource::make($good)->load(['sales']));
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


