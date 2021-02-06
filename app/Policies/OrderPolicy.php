<?php

namespace App\Policies;


use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Order $order
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if
        (($user->role === User::ROLE_ADMIN)
            or
            DB::table('orders')->where('buyer_id', $user->id))
        {
            return true;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function create(User $user)
    {
        if
        (($user->role === User::ROLE_ADMIN)
            or
            ($user->id == request()->buyer_id))
        {
            return true;
       }
    }
    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Order $order
     * @return mixed
     */
    public function view(User $user, Order $order)
    {
        if ($user->role === User::ROLE_ADMIN or $user->id === $order->buyer_id) {
            return true;
       }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\User $user
     * @param \App\Models\Order $order
     * @return mixed
     */
    public function update(User $user, Order $order)
    {
        if ($user->role === User::ROLE_ADMIN or $user->id === $order->buyer_id)
        {
            return true;
        }

    }



/**
* Determine whether the user can delete the model.
*
* @param \App\Models\User $user
* @return mixed
*/
    public function delete(User $user)
    {
        if ($user->role === User::ROLE_ADMIN) {
            return true;
        }
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */
    public function restore(User $user)
    {
        if ($user->role === User::ROLE_ADMIN) {
            return true;
        }
    }

    public function good_in_basket(User $user, Order $order)
    {
        if (($user->role === User::ROLE_ADMIN)
            or ($user->id === $order->buyer_id))
        {
            return true;
        }

    }

    public function good_out_basket(User $user, Order $order)
    {
        if (($user->role === User::ROLE_ADMIN)
           or ($user->id === $order->buyer_id))
        {
            return true;
        }
    }

}
