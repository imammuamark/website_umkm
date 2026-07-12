<?php

namespace App\Policies;

use App\Models\BusinessProfile;
use App\Models\User;

class BusinessProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage settings') || $user->hasPermissionTo('manage content');
    }

    public function view(User $user, BusinessProfile $profile): bool
    {
        return $user->hasPermissionTo('manage settings') || $user->hasPermissionTo('manage content');
    }

    public function create(User $user): bool
    {
        // Singleton, shouldn't manually create multiple
        return false;
    }

    public function update(User $user, BusinessProfile $profile): bool
    {
        return $user->hasPermissionTo('manage settings') || $user->hasPermissionTo('manage content');
    }

    public function delete(User $user, BusinessProfile $profile): bool
    {
        return false;
    }
}
