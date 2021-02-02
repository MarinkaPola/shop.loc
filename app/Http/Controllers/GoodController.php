<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoodUserRequest;
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
    { if (request()->input('search') =='') {
        $goods = Good::all();
        return GoodResource::collection($goods);
    }

    else {
        $goods = Good::where(function ($query) {
            $query->where('title', 'like', request()->input('search'))
                ->orWhere('feature', 'like', request()->input('search'));
        })->paginate();
        return $this->success(GoodResourceCollection::make($goods));
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

    public function good_in_basket(GoodUserRequest $request)
    {
        /** @var Good $good */
        $good = Good::find($request->good_id);
        $this->authorize('good_in_basket', $good);
     if (($good->goodUsers()->where('user_id', $request->user_id)->doesntExist()) and($good->count < $request->count)){

        return $this->error('Reduce the quantity of purchased goods');
    }
      else  if (($good->goodUsers()->where('user_id', $request->user_id)->exists()) and
          (($good->goodUsers()->where('user_id', $request->user_id)->first()->pivot->count)+($request->count))<=$good->count)
      {
          $good_count = $good->goodUsers()->where('user_id', $request->user_id)->first()->pivot->count+($request->count);
          DB::table('good_user')->where('user_id', $request->user_id)->update(['count' => $good_count]);
            return $this->success('The number of items in the basket is now '.$good_count);
        }
      else  if (($good->goodUsers()->where('user_id', $request->user_id)->exists()) and
          (($good->goodUsers()->where('user_id', $request->user_id)->first()->pivot->count)+($request->count))>$good->count)
      {
          return $this->error('You want to buy more than you have in stock');
      }

        else{
        $good->goodUsers()->attach($request->user_id, ['count' => $request->count]);
            return $this->success('You have added the number of goods to your basket '.$request->count);
    }
    }

    public function good_out_basket(GoodUserRequest $request)
    {
        /** @var Good $good */

        $good = Good::find($request->good_id);
        $this->authorize('good_out_basket', $good);
         if (($good->goodUsers()->where('user_id', $request->user_id)->exists()) and
             ($request->count)>0)
         {
             $good_count = $request->count;
             DB::table('good_user')->where('user_id', $request->user_id)->where('good_id', $request->good_id)->update(['count' => $good_count]);
             return $this->success('The number of items in the basket is now '.$good_count);
         }
         else

        {
             $good->goodUsers()->detach($request->user_id);

             return $this->success('You have canceled the purchase of this product');
         }
        }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function good_sort_by_price() {
        /** @var Good $good */
       // $this->authorize(Good::class);
        $goods = Good::paginate()->sortBy('price');
        return $this->success($goods);
    }
    public function good_sort_by_desc_price() {
        /** @var Good $good */
        //$this->authorize(Good::class);
        $goods = Good::paginate()->sortByDesc('price');
        return $this->success($goods);
    }

    public function good_sort_by_sale() {
        /** @var Good $good */
       // $this->authorize(Good::class);
        $goods = Good::paginate()->sortBy('sale');
        return $this->success($goods);
    }

    public function good_sort_by_desc_sale() {
        /** @var Good $good */
       // $this->authorize(Good::class);
        $goods = Good::paginate()->sortByDesc('sale');
        return $this->success($goods);
    }
    }


