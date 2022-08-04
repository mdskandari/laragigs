<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Listing;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Str;

class ListingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        if ($user->id == 1 ){
            return true;
        }
//        return false;

    }

    /**
     * Determine whether the user can view the model.
     *
     * @param User $user
     * @param Listing $listing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Listing $listing)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user): Response|bool
    {
        return $user->hasRoles(User::ADMIN,User::USER);
//                ? Response::allow()
//                : Response::deny('only admin can create Listings');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User $user
     * @param Listing $listing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Listing $listing)
    {
        return $listing->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User $user
     * @param Listing $listing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Listing $listing)
    {
        return $listing->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User $user
     * @param Listing $listing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Listing $listing)
    {
        if (Str::lower($user->name) === 'admin'){
            return  true;
        }

    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User $user
     * @param Listing $listing
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Listing $listing)
    {
        //
    }



}
