<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Post;
use App\Repositories\EloquentRepositoryInterface;


class PostsController extends Controller
{
    protected EloquentRepositoryInterface $modelInterface;

    public function __construct(EloquentRepositoryInterface $modelInterface)
    {
        $this->middleware('auth')->only(['create','update']);
        $this->middleware('can:update,post')->only(['edit', 'update', 'destroy']);
        $this->modelInterface = $modelInterface;
    }

    public function index()
    {
        $currentPage = request()->get('page',1);
        $posts = $this->modelInterface->publicIndex(auth()->user(), ['page' => $currentPage]);
            return view('posts.index', compact( 'posts'));
    }

    public function show(Post $post)
    {
        $post = $this->modelInterface->find($post, auth()->user());

        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostStoreAndUpdateRequest $request)
    {
        $this->modelInterface->store($request);

        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        $post = $this->modelInterface->find($post, auth()->user());

        $isAdmin = false;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    public function update(PostStoreAndUpdateRequest $request, Post $post)
    {
        $this->modelInterface->update($request, $post);

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {

        $this->modelInterface->destory($post);

        return redirect()->route('posts.index');
    }
}
