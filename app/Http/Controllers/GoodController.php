<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoodOrderRequest;
use App\Models\User;
use App\Models\Good;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\GoodRequest;
use App\Http\Resources\GoodResource;
use App\Http\Resources\GoodResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\Pivot;

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
        if (request()->input('search') =='')
        {
        $goods = Good::all();
        if (request()->input('sortBy') =='price' and request()->input('sortByDesc') ==''){
        $goods_sortByPrice = $goods->sortBy('price');
            return $this->success($goods_sortByPrice);
        }
        elseif (request()->input('sortBy') =='sale' and request()->input('sortByDesc') ==''){
        $goods_sortBySale = $goods->sortBy('sale');
            return $this->success($goods_sortBySale);
        }
        elseif (request()->input('sortBy') =='' and request()->input('sortByDesc') =='price'){
        $goods_sortByDescPrice = $goods->sortByDesc('price');
            return $this->success($goods_sortByDescPrice);
        }
        elseif (request()->input('sortBy') =='' and request()->input('sortByDesc') =='sale'){
        $goods_sortByDescSale = $goods->sortByDesc('sale');
            return $this->success($goods_sortByDescSale);
        }
        else{
        return GoodResource::collection($goods);
        }
        }

    else {
        $goods = Good::where(function ($query) {
            $query->where('title', 'like', request()->input('search'))
                ->orWhere('feature', 'like', request()->input('search'));
        })->paginate();
        if (request()->input('sortBy') =='' and request()->input('sortByDesc') =='')
        {
        return $this->success(GoodResourceCollection::make($goods));
        }
        elseif (request()->input('sortBy') =='price' and request()->input('sortByDesc') ==''){
            $goods_sortByPrice = $goods->sortBy('price');
            return $this->success($goods_sortByPrice);
        }
        elseif (request()->input('sortBy') =='sale' and request()->input('sortByDesc') ==''){
            $goods_sortBySale = $goods->sortBy('sale');
            return $this->success($goods_sortBySale);
        }
        elseif (request()->input('sortBy') =='' and request()->input('sortByDesc') =='price'){
            $goods_sortByDescPrice = $goods->sortByDesc('price');
            return $this->success($goods_sortByDescPrice);
        }
        elseif (request()->input('sortBy') =='' and request()->input('sortByDesc') =='sale'){
            $goods_sortByDescSale = $goods->sortByDesc('sale');
            return $this->success($goods_sortByDescSale);
        }
        }

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
        return $this->success(GoodResource::make($good));
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
        try {
            $good->delete();
        } catch (Exception $e) {
            return null;
        }
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }



    }


