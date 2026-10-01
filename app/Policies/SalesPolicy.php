<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;

class SalesPolicy
{
    /**
     * El administrador siempre puede ver y gestionar todo.
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    public function view(User $user, Sale $sale): bool
    {
        return (int)$user->id === (int)$sale->user_id;
    }

    public function update(User $user, Sale $sale): bool
    {
        return (int)$user->id === (int)$sale->user_id;
    }

    public function delete(User $user, Sale $sale): bool
    {
        return (int)$user->id === (int)$sale->user_id;
    }
}