<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoodOrderRequest;
use App\Models\User;
use App\Models\Order;
use App\Models\Good;
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
        $orders = Order::all();
        return OrderResource::collection($orders);
        }
        else if (DB::table('orders')->where('buyer_id', $user->id)->exists())
        {
            $orders = Order::where('buyer_id', '=', $user->id)->get();
            return OrderResource::collection($orders);
        }
    }
    public function store(OrderRequest $request)

    {
        $order = Order::create($request->validated());
        return $this->created(OrderResource::make($order));
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
        $good = Good::find($request->good_id);
        $order=Order::find($request->order_id);
        $this->authorize('good_in_basket', $order);
        if (($good->goodOrders()->where('order_id', $request->order_id)->doesntExist()) and ($good->count >= $request->count))
        {
            $order->orderGoods()->attach($request->good_id, ['count' => $request->count]);
            $good_title = $good->title;
            return $this->success('Added to basket '.$good_title);
        }

   else  if (($good->goodOrders()->exists()) and   ($good->goodOrders()->where('order_id', $request->order_id))){
       /** @var $summa */
       $summa =0;
       foreach ($good->goodOrders as $order) {
           $summa=$summa+$order->pivot->count;
       }
       if (($summa+($request->count))<=$good->count){
        $good_count = $good->goodOrders()->where('order_id', $request->order_id)->first()->pivot->count+($request->count);
        DB::table('good_order')->where('order_id', $request->order_id)->where('good_id',$request->good_id )->update(['count' => $good_count]);
        return $this->success('The number of items in the basket is now '.$good_count);}
    else{
        return $this->error('You want to buy more than you have in stock');
    }
    }
    }

    public function good_out_basket(GoodOrderRequest $request){
        /** @var Good $good */

        $good = Good::find($request->good_id);
        $order = Order::find($request->order_id);
        $this->authorize('good_out_basket', $order);
        if (($good->goodOrders()->where('order_id', $request->order_id)->exists()) and ($request->count)>0)
        {
            $good_count = $request->count;
            DB::table('good_order')->where('order_id', $request->order_id)->where('good_id', $request->good_id)->update(['count' => $good_count]);
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
     * @param \App\Models\Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(OrderRequest $request, Order $order)
    {
        $order->update($request->validated());
        return $this->success(OrderResource::make($order));
    }




    public function destroy(Order $order)
    {
        try {
            $order->delete();
        } catch (Exception $e) {
            return null;
        }
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }

    }
