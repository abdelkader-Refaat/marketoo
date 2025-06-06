<?php

namespace Modules\Posts\App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Modules\Posts\App\Http\Requests\StorePostRequest;
use Modules\Posts\App\Http\Requests\UpdatePostRequest;
use Modules\Posts\App\Models\Post;

class PostController extends Controller
{
    public function index()
    {
        // dd(auth()->id());
        $posts = auth()->user()->posts()->latest()->get();

        return Inertia::render('posts/index', [
            'posts' => $posts,
        ]);
    }

    public function store(StorePostRequest $request)
    {
        $post = auth()->user()->posts()->create($request->validated());

        return redirect()
            ->route('site.posts.index')
            ->with('success', __('admin.added_successfully'));
    }

    public function create()
    {
        return Inertia::render('posts/create');
    }

    public function show(Post $post)
    {
        return Inertia::render('posts/show', [
            'post' => $post->load('user'),
            'can' => [
                'update' => true, // Since we're getting user's posts, they can always update
                'delete' => true, // Since we're getting user's posts, they can always delete
            ],
        ]);
    }

    public function edit(Post $post)
    {
        return Inertia::render('posts/edit', [
            'post' => $post,
            'errors' => session()->get('errors')?->getBag('default')?->getMessages(),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());

        return redirect()
            ->route('site.posts.index')
            ->with('success', __('admin.update_successfullay'));
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()
            ->route('site.posts.index')
            ->with('success', __('admin.deleted_successfully'));
    }

    public function events()
    {
        return Inertia::render('posts/events', [
            'posts' => auth()->user()->posts()
                ->whereNotNull('event_name')
                ->latest()
                ->get(),
        ]);
    }

    public function promoted()
    {
        return Inertia::render('posts/promoted', [
            'posts' => auth()->user()->posts()
                ->where('is_promoted', true)
                ->latest()
                ->get(),
        ]);
    }

    public function archived()
    {
        return Inertia::render('posts/archived', [
            'posts' => auth()->user()->posts()
                ->where('is_archived', true)
                ->latest()
                ->get(),
        ]);
    }

    public function settings()
    {
        return Inertia::render('posts/settings');
    }
}
