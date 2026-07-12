<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;

class LocationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage content');
    }

    public function view(User $user, Location $location): bool
    {
        return $user->hasPermissionTo('manage content');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage content');
    }

    public function update(User $user, Location $location): bool
    {
        return $user->hasPermissionTo('manage content');
    }

    public function delete(User $user, Location $location): bool
    {
        return $user->hasPermissionTo('manage content');
    }
}
