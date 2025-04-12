<?php

namespace Modules\Posts\App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Modules\Posts\App\Http\Requests\StorePostRequest;
use Modules\Posts\App\Http\Requests\UpdatePostRequest;
use Modules\Posts\App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->posts()->latest()->get();
        return Inertia::render('posts/index', [
            'posts' => $posts,
        ]);
    }

    public function store(StorePostRequest $request)
    {
        auth()->user()->posts()->create($request->validated());
        return redirect()->route('site.posts.index')->with('success', 'Post created successfully.');
    }

    public function create()
    {
        return Inertia::render('posts/create');
    }

    public function show(Post $post)
    {
        return Inertia::render('posts/show', [
            'post' => $post,
        ]);
    }

    public function edit(Post $post)
    {
        return Inertia::render('posts/edit', [
            'post' => $post,
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());
        return redirect()->route('site.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('site.posts.index')->with('success', 'Post deleted successfully.');
    }

    public function events()
    {
        $posts = auth()->user()->posts()->whereNotNull('event_name')->latest()->get();
        return Inertia::render('posts/events', [
            'posts' => $posts,
        ]);
    }

    public function promoted()
    {
        $posts = auth()->user()->posts()->where('is_promoted', true)->latest()->get();
        return Inertia::render('posts/promoted', [
            'posts' => $posts,
        ]);
    }

    public function archived()
    {
        $posts = auth()->user()->posts()->where('is_archived', true)->latest()->get();
        return Inertia::render('posts/archived', [
            'posts' => $posts,
        ]);
    }

    public function settings()
    {
        return Inertia::render('posts/settings');
    }
}
