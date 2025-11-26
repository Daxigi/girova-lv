<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Product $product): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('create products');
    }

    public function update(User $user, Product $product): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('edit products');
    }

    public function delete(User $user, Product $product): bool
    {
        return $user->hasRole('admin') || $user->hasPermissionTo('delete products');
    }

    public function restore(User $user, Product $product): bool
    {
        return $user->hasRole('admin');
    }

    public function forceDelete(User $user, Product $product): bool
    {
        return $user->hasRole('admin');
    }
}
