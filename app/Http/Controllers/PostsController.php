<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Post;
use App\Repositories\EloquentRepositoryInterface;


class PostsController extends Controller
{
    protected EloquentRepositoryInterface $modelRepositoryInterface;

    public function __construct(EloquentRepositoryInterface $modelRepositoryInterface)
    {
        $this->middleware('auth')->only(['create','update']);
        $this->middleware('can:update,post')->only(['edit', 'update', 'destroy']);
        $this->modelRepositoryInterface = $modelRepositoryInterface;
    }

    public function index()
    {
        $currentPage = request()->get('page',1);
        $posts = $this->modelRepositoryInterface->publicIndex(auth()->user(), ['page' => $currentPage]);
            return view('posts.index', compact( 'posts'));
    }

    public function show(Post $post)
    {
        $post = $this->modelRepositoryInterface->find($post, auth()->user());

        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostStoreAndUpdateRequest $request)
    {
        $this->modelRepositoryInterface->store($request);

        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        $post = $this->modelRepositoryInterface->find($post, auth()->user());

        $isAdmin = false;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    public function update(PostStoreAndUpdateRequest $request, Post $post)
    {
        $this->modelRepositoryInterface->update($request, $post);

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {

        $this->modelRepositoryInterface->destory($post);

        return redirect()->route('posts.index');
    }
}
