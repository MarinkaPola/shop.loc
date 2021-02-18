<?php

namespace App\Http\Controllers;

use App\Http\Resources\GoodResource;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserResourceCollection;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(User::class);
    }

    /**
     * Display a listing of the resource.
     *
    @return JsonResponse
     */
    public function index()
    {
            $users = User::paginate();
        return $this->success(UserResourceCollection::make($users));
    }


    /**
     * Display the specified resource.
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return $this->success(UserResource::make($user));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());
        return $this->success(UserResource::make($user));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user)
    {
           $user ->delete();
        return $this->success('Record deleted.', JsonResponse::HTTP_NO_CONTENT);
    }

    public function basket_now()
        {
        $user=auth()->user();
      //  $goods_in_basket= $user->order()->whereNull('payment')->whereNull('delivery')->latest()->firstOrCreate();
        $goods_in_basket = $user->cart;
        return  $this->success($goods_in_basket);
        }

}
