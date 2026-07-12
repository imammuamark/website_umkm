<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('manage articles');
    }

    public function view(User $user, Article $article): bool
    {
        return $user->hasPermissionTo('manage articles');
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('manage articles');
    }

    public function update(User $user, Article $article): bool
    {
        return $user->hasPermissionTo('manage articles');
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->hasPermissionTo('manage articles');
    }
}
