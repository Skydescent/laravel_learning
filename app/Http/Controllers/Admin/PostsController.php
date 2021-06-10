<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Post;

class PostsController extends Controller
{
    public function index()
    {
        $currentPage = request()->get('page',1);
        $posts = $this->modelInterface->adminIndex(auth()->user(), ['page' => $currentPage] );
        return view('admin.posts.index', compact( 'posts'));
    }

    public function update(PostStoreAndUpdateRequest $request, Post $post)
    {
        $this->modelInterface->update($request, $post, auth()->user());
        return redirect()->route('admin.posts.index');
    }

    public function edit(Post $post)
    {
        $post = $this->modelInterface->find($post, auth()->user());
        $isAdmin = true;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    public function destroy(Post $post)
    {
        $this->modelInterface->destory($post, auth()->user());
        flash('Статья удалена', 'warning');
        return redirect()->route('admin.posts.index');
    }
}
