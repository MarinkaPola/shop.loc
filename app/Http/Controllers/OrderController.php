<?php

namespace App\Http\Controllers;

use App\Http\Requests\GoodUserRequest;
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  OrderRequest $request
     * @return JsonResponse
     */
    public function store(OrderRequest $request)

    {
        $order = Order::create($request->validated());
        return $this->created(OrderResource::make($order));
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
       return $this->success(OrderResource::make($order->load(['buyer.userGoods'])));

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
