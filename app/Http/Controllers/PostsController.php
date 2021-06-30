<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreAndUpdateRequest;


class PostsController extends Controller
{
    protected \App\Service\RepositoryServiceable $postsService;

    public function __construct()
    {
        $this->middleware('auth')->only(['create','update']);
        $this->postsService = new \App\Service\PostsService();
    }

    public function index()
    {
        $currentPage = request()->get('page',1);
        $posts = $this->postsService->publicIndex(request()->user(), ['page' => $currentPage]);
        return view('posts.index', compact( 'posts'));
    }

    public function show($slug)
    {
        $post = $this->postsService->find($slug, auth()->user());
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostStoreAndUpdateRequest $request)
    {
        $this->postsService->store($request);

        return redirect()->route('posts.index');
    }

    public function edit($slug)
    {
        $post = $this->postsService->find($slug, auth()->user());
        $this->authorize('update', $post->model);

        $isAdmin = false;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    public function update(PostStoreAndUpdateRequest $request, $slug)
    {
        $post = $this->postsService->find($slug, auth()->user());
        $this->authorize('update', $request->post->model);
        $this->postsService->update($request, $slug);

        return redirect()->route('posts.index');
    }

    public function destroy($slug)
    {
        $post = $this->postsService->find($slug, auth()->user());
        $this->authorize('update', $post->model);

        $this->postsService->destroy($slug, auth()->user());

        return redirect()->route('posts.index');
    }
}
