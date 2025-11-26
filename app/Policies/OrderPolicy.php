<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {

        return $user->hasRole('admin') || $user->hasRole('employee');
    }

    public function view(User $user, Order $order): bool
    {
        return 
            $user->id === $order->user_id
                || $user->hasRole('admin')
                || $user->hasRole('employee');
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Order $order): bool
    {
        return false;
    }

    public function updateStatus(User $user, Order $order): bool
    {
        return $user->hasRole('admin') || $user->hasRole('employee');
    }

    public function delete(User $user, Order $order): bool
    {
        return false;
    }

    public function restore(User $user, Order $order): bool
    {
        return false;
    }

    public function forceDelete(User $user, Order $order): bool
    {
        return false;
    }
}
