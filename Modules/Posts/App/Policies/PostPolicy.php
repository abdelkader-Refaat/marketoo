<?php

namespace Modules\Posts\App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Admins\App\Models\Admin;
use Modules\Posts\App\Models\Post;
use Modules\Users\App\Models\User;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function view(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }

    public function update(User|Admin $user, Post $post)
    {
        return $user instanceof Admin || $user->id === $post->user_id;
    }

    public function delete(User|Admin $user, Post $post)
    {
        return $user instanceof Admin || $user->id === $post->user_id;
    }
}
