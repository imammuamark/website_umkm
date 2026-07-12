<?php

namespace App\Policies;

use App\Models\ContactMessage;
use App\Models\User;

class ContactMessagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view leads');
    }

    public function view(User $user, ContactMessage $message): bool
    {
        return $user->hasPermissionTo('view leads');
    }

    public function create(User $user): bool
    {
        // Public form creates this, not admin
        return false;
    }

    public function update(User $user, ContactMessage $message): bool
    {
        return $user->hasPermissionTo('manage leads');
    }

    public function delete(User $user, ContactMessage $message): bool
    {
        return $user->hasPermissionTo('manage leads');
    }
}
