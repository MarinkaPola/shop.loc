<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoodOrderRequest;
use App\Models\User;
use App\Models\Order;
use App\Models\Good;
use App\Models\Area;
use App\Models\Sale;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderResourceCollection;
use App\Notifications\OrderAccepted;


class OrderController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Order::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->role === User::ROLE_ADMIN) {
            $orders = Order::paginate();
        } else {
            $orders = $user->order()->whereNotNull('payment')->latest()->get();
        }
        return $this->created(OrderResource::collection($orders->load(['orderGoods'])));
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
        if ($user->order()->whereNull('payment')->whereNull('delivery')->latest()->first()) {
            return $this->success('You already have an item in your cart');
        }
        $order = $user->order()->create();
        return $this->created(OrderResource::make($order));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param OrderRequest $request
     * @param \App\Models\Good $good
     * @return JsonResponse
     */
    public function good_in_basket(GoodOrderRequest $request)

    {
        /** @var User $user */
        $user = auth()->user();
        /** @var Order $order */
        $order = $user->order()->whereNull('payment')->whereNull('delivery')->latest()->firstOrCreate();
        /** @var Good $good */
        $good = Good::find($request->good_id);
        if (($good->goodOrders()->where('order_id', $order->id)->doesntExist()) and ($good->count >= $request->count)) {
            $order->orderGoods()->attach($request->good_id, ['count' => $request->count]);
            $good_title = $good->title;
            return $this->success('Added to basket ' . $good_title);
        }
        if ($good->goodOrders()->exists()) {
            $summa = 0;
            foreach ($good->goodOrders as $order) {
                $summa = $summa + $order->pivot->count;  // сумма количества данного товара, который уже в корзине,включая у других пользователей
            }
            //dd($summa+($request->count));
            if (($summa + ($request->count - $order->pivot->count)) <= $good->count) {
                //$good_count = $good->goodOrders()->where('order_id', $order->id)->first()->pivot->count+($request->count);
                $order->orderGoods()->updateExistingPivot($good, ['count' => $request->count]);
                return $this->success('The number of items in the basket is now ' . $request->count);
            }
            return $this->error('You want to buy more than you have in stock', 422);
        }
        return  $this->error('Product out of stock', 422);
    }

    public function good_out_basket(GoodOrderRequest $request)
    {
        /** @var User $user */
        $user = auth()->user();
        $order = $user->order()->whereNull('payment')->whereNull('delivery')->latest()->first();
        /** @var Good $good */
        $good = Good::find($request->good_id);
        // $this->authorize('good_out_basket', $order);
        if ($order && ($good->goodOrders()->where('order_id', $order->id)->exists()) && ($request->count) >= 1) {
            $good_count = $request->count;
            $order->orderGoods()->updateExistingPivot($good, ['count' => $good_count]);
            return $this->success('The number of items in the basket is now ' . $good_count);
        }
        $good->goodOrders()->detach($request->order_id);
        return $this->success('You have canceled the purchase of this product');

    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @param User $user
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
     * @param User $user
     * @param Order $order
     * @return JsonResponse
     */
    public function update(OrderRequest $request, Order $order)
    {
        /** @var User $user */
        $user = auth()->user();
        if ($user->role === User::ROLE_USER) {
            $sum = 0;
            /** @var Good $good */
            foreach ($order->orderGoods as $good) {
                $sales_good = $good->sales->pluck('value_percentage');

                $sales_category = $good->category->sales->pluck('value_percentage');
                //работает усли category i area no null
                $sales_area = $good->category->areaCategory->sales->pluck('value_percentage');
                $masiv_sales = $sales_good->concat($sales_category)->concat($sales_area);
                $max_sale = $masiv_sales->max();
                $sum = $sum + round(($good->pivot->count) * ($good->price) * ((100 - $max_sale) / 100), 2);
            }
            $order->update($request->validated() + ['sum' => $sum]);
            $user->notify(new OrderAccepted($order));
        } elseif ($user->role === User::ROLE_ADMIN) {
            $order->update($request->validated());
        }
        return $this->success(OrderResource::make($order));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Order $order
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function updatePayment(Order $order)  //for admin
    {
        $this->authorize('updatePayment', $order);
        /** @var User $user */
        $user = auth()->user();
        if ($user->role === User::ROLE_ADMIN) {
            $order->update(['goods_is_paid' => 1]);
        }
        return $this->success(OrderResource::make($order));
    }
}
