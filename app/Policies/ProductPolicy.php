<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param User $user
     *
     * @return Response|bool
     */
    public function viewAny(User $user): Response|bool
    {
        return $user->roles()->first()->id === User::IS_SUPPLIER || $user->roles()->first()->id === User::IS_CLIENT;
    }


    /**
     * Determine whether the user can view the model.
     *
     * @param User    $user
     * @param Product $product
     *
     * @return Response|bool
     */
    public function view(User $user, Product $product): Response|bool
    {
        $role = $user->roles()->first()->id;

        if ($role === User::IS_CLIENT) {
            return Response::allow();
        } elseif ($role === User::IS_SUPPLIER && $this->_hasSupplier($product, $user)) {
            return Response::allow();
        } else {
            return Response::denyWithStatus(403);
        }

    }

    /**
     * Determine whether the user can create models.
     *
     * @param User $user
     *
     * @return Response|bool
     */
    public function create(User $user): Response|bool
    {
        return $user->roles()->first()->id === User::IS_SUPPLIER;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param User    $user
     * @param Product $product
     *
     * @return Response|bool
     */
    public function update(User $user, Product $product): Response|bool
    {
        $role = $user->roles()->first()->id;

        if ($role === User::IS_SUPPLIER && $this->_hasSupplier($product, $user)) {
            return Response::allow();
        } else {
            return Response::denyWithStatus(403);
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param User    $user
     * @param Product $product
     *
     * @return Response|bool
     */
    public function delete(User $user, Product $product): Response|bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param User    $user
     * @param Product $product
     *
     * @return Response|bool
     */
    public function restore(User $user, Product $product): Response|bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param User    $user
     * @param Product $product
     *
     * @return Response|bool
     */
    public function forceDelete(User $user, Product $product): Response|bool
    {
        //
    }

    /**
     * Determine if supplier its own the product
     *
     * @param Product $product
     * @param User    $user
     *
     * @return bool
     */
    private function _hasSupplier(Product $product, User $user): bool
    {
        try {
            Product::where('id', '=', $product->id)
                ->whereHas('users', function (Builder $query) use ($user) {
                    return $query->where('id', '=', $user->id);
                })
                ->firstOrFail();
        } catch (ModelNotFoundException $e) {
            return false;
        }

        return true;
    }
}
