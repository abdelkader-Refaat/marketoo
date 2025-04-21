<?php

namespace Modules\Posts\App\Observers;

use App\Models\Core\Notification;
use Modules\Posts\app\Models\Post;

class PostObserver
{
    /**
     * Handle the Post "created" event.
     */
    public function created(Post $post): void
    {
        // notify followers about new follower post
//        Notification::send($post->user, new \Modules\Posts\App\Notifications\NewPost($post));
    }

    /**
     * Handle the Post "updated" event.
     */
    public function updated(Post $post): void
    {
    }

    /**
     * Handle the Post "deleted" event.
     */
    public function deleted(Post $post): void
    {
    }

    /**
     * Handle the Post "restored" event.
     */
    public function restored(Post $post): void
    {
    }

    /**
     * Handle the Post "force deleted" event.
     */
    public function forceDeleted(Post $post): void
    {
    }
}
