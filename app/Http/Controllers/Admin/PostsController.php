<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Post;
use App\Service\PostsService;

class PostsController extends Controller
{
    private $postService;

    public function __construct(PostsService $postService)
    {
        $this->postService = $postService;
    }

    public function index()
    {
        $posts = Post::latest()->with('owner')->paginate(20);
        return view('admin.posts.index', compact( 'posts'));
    }

    public function update(PostStoreAndUpdateRequest $request, Post $post)
    {
        $this->postService
            ->setPost($post)
            ->storeOrUpdate($request->validated());
        return redirect()->route('admin.posts.index');
    }

    public function edit(Post $post)
    {
        $isAdmin = true;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    public function destroy(Post $post)
    {
        $this->postService
            ->setPost($post)
            ->destroy();
        flash('Статья удалена', 'warning');
        return redirect()->route('admin.posts.index');
    }
}
