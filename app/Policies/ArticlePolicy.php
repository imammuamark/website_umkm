<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;

class ArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view articles') || $user->can('manage articles');
    }

    public function view(User $user, Article $article): bool
    {
        return $user->can('view articles') || $user->can('manage articles');
    }

    public function create(User $user): bool
    {
        return $user->can('create articles') || $user->can('manage articles');
    }

    public function update(User $user, Article $article): bool
    {
        return $user->can('update all articles')
            || ($user->can('update own articles') && $article->author_id === $user->id)
            || $user->can('manage articles');
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->can('delete articles') || $user->can('manage articles');
    }

    public function restore(User $user, Article $article): bool
    {
        return $user->can('delete articles') || $user->can('manage articles');
    }

    public function forceDelete(User $user, Article $article): bool
    {
        return $user->can('delete articles');
    }

    public function publish(User $user, Article $article): bool
    {
        return $user->can('publish articles');
    }
}
