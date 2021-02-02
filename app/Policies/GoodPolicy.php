<?php

namespace App\Policies;
use Illuminate\Http\Request;
use App\Models\Good;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GoodPolicy
{
use HandlesAuthorization;

/**
* Determine whether the user can view any models.
*
* @param \App\Models\User $user
* @return mixed
*/
public function viewAny(User $user)
{
return true;
}

/**
* Determine whether the user can create models.
*
* @param \App\Models\User $user
* @return mixed
*/
public function create(User $user)
    {
    if ($user->role === User::ROLE_ADMIN) {
    return true;
   }
    }

/**
* Determine whether the user can view the model.
*
* @param \App\Models\User $user
* @return mixed
*/

public function view(User $user)
{
return true;
}

/**
* Determine whether the user can update the model.
*
* @param \App\Models\User $user
* @return mixed
*/
public function update(User $user)
{
   if ($user->role === User::ROLE_ADMIN){
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
    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\User $user
     * @return mixed
     */

    public function good_in_basket(User $user, Good $good)
    {
        if (($user->role === User::ROLE_ADMIN)
            or ($user->id === request()->user_id))
        {
            return true;
        }

    }

    public function good_out_basket(User $user, Good $good)
    {

        if (($user->role === User::ROLE_ADMIN)
            or ($user->id === request()->user_id))
        {
            return true;
        }
    }

    public function good_sort_by_price(User $user)
    {
        return true;

    }

    public function good_sort_by_desc_price(User $user)
    {
        return true;
    }

    public function good_sort_by_sale(User $user)
    {
        return true;
    }
    public function good_sort_by_desc_sale(User $user)
    {
        return true;
    }
}
