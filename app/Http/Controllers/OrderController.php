<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoodOrderRequest;
use App\Models\User;
use App\Models\Order;
use App\Models\Good;
use App\Models\Area;
use App\Models\Sale;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderResourceCollection;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class);
    }
    /**
     * Display a listing of the resource.
     *
    @return JsonResponse
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->role === User::ROLE_ADMIN)
        {
        $orders = Order::paginate();
        }
        else
        {
            $orders = $user->order()->get();
        }
        return $this->created(OrderResource::collection($orders));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return JsonResponse
     */
    public function store()

    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->order()->whereNull('payment')->whereNull('delivery')->latest()->first())
        {
            return $this->success('You already have an item in your cart');
        }
        else {
            $order = $user->order()->create();
            return $this->created(OrderResource::make($order));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  OrderRequest $request
     * @param  \App\Models\Good  $good
     * @return JsonResponse
     */
    public function good_in_basket(GoodOrderRequest $request)

    {
        /** @var User $user */
        $user = auth()->user();
        /** @var Order $order */
        $order = $user->order()->whereNull('payment')->whereNull('delivery')->latest()->first();
        /** @var Good $good */
        $good = Good::find($request->good_id);
        if (($good->goodOrders()->where('order_id', $order->id)->doesntExist()) and ($good->count >= $request->count))
        {
            //$order = $user->order()->create();
            $order->orderGoods()->attach($request->good_id, ['count' => $request->count]);
            $good_title = $good->title;
            return $this->success('Added to basket '.$good_title);
        }

        else  if ($good->goodOrders()->exists()){
            $summa =0;
            foreach ($good->goodOrders as $order) {
                $summa=$summa+$order->pivot->count;  //для Макса - сумма количества данного товара, который уже в корзине,включая у других пользователей
            }
            //dd($summa+($request->count));
            if (($summa+($request->count))<=$good->count){
                $good_count = $good->goodOrders()->where('order_id', $order->id)->first()->pivot->count+($request->count);
                $order->orderGoods()->updateExistingPivot($good,['count' => $good_count]);
                return $this->success('The number of items in the basket is now '.$good_count);}
            else{
                return $this->error('You want to buy more than you have in stock');
            }
        }
    }

    public function good_out_basket(GoodOrderRequest $request){
        /** @var User $user */
        $user = auth()->user();
        $order = $user->order()->whereNull('payment')->whereNull('delivery')->latest()->first();
        /** @var Good $good */
        $good = Good::find($request->good_id);
       // $this->authorize('good_out_basket', $order);
        if (($good->goodOrders()->where('order_id', $order->id)->exists()) and ($request->count)>0)
        {
            $good_count = $request->count;
            $order->orderGoods()->updateExistingPivot($good,['count' => $good_count]);
            return $this->success('The number of items in the basket is now '.$good_count);
        }
        else {
            $good->goodOrders()->detach($request->order_id);
            return $this->success('You have canceled the purchase of this product');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @param  \App\Models\User  $user
     * @return JsonResponse
     */
    public function show(Order $order, User $user)
    {
       return $this->success(OrderResource::make($order->load(['orderGoods'])));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(OrderRequest $request, Order $order)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->role === User::ROLE_USER) {
        /** @var Order $order */
        $order = $user->order()->whereNull('payment')->whereNull('delivery')->latest()->first();
        $sum =0;
        /** @var Good $good */
        foreach ($order->orderGoods as $good) {
            $sales_good = $good->sales->toArray();
            $sales_category = $good->category->sales->toArray();
            $sales_area = $good->category->areaCategory->sales->toArray();
            $masiv_sales = array_merge($sales_good, $sales_category, $sales_area);
            $masiv_value_percentage = array_column($masiv_sales, 'value_percentage');
            $max_sale = max($masiv_value_percentage);
            $sum=$sum+($good->pivot->count)*($good->price)*((100-$max_sale)/100);
        }

        $order->update($request->validated()+['sum'=>$sum]);
        }
        elseif ($user->role === User::ROLE_ADMIN){
            $order->update(['goods_is_paid'=>1]);
        }
        return $this->success(OrderResource::make($order));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Order $order)
    {
            $order->delete();
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }

    }
