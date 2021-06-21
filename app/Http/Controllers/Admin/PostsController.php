<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Post;
use App\Repositories\EloquentRepositoryInterface;

class PostsController extends Controller
{
    protected EloquentRepositoryInterface $modelRepositoryInterface;

    public function __construct(EloquentRepositoryInterface $modelRepositoryInterface)
    {
        $this->modelRepositoryInterface = $modelRepositoryInterface;
    }

    public function index()
    {
        $currentPage = request()->get('page',1);
        $posts = $this->modelRepositoryInterface->adminIndex(auth()->user(), ['page' => $currentPage] );
        return view('admin.posts.index', compact( 'posts'));
    }

    public function update(PostStoreAndUpdateRequest $request, Post $post)
    {
        $this->modelRepositoryInterface->update($request, $post);
        return redirect()->route('admin.posts.index');
    }

    public function edit(Post $post)
    {
        $post = $this->modelRepositoryInterface->find($post);
        $isAdmin = true;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    public function destroy(Post $post)
    {
        $this->modelRepositoryInterface->destroy($post, auth()->user());
        flash('Статья удалена', 'warning');
        return redirect()->route('admin.posts.index');
    }
}
