<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Product $proudct): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Proudct $proudct): bool
    {
        if($user->role == 'vendor' && $user->id == $proudct->store->user_id || $user->role == 'admin'){
            return true;
        }
        return false;
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Proudct $proudct): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Proudct $proudct): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Proudct $proudct): bool
    {
        //
    }
}
