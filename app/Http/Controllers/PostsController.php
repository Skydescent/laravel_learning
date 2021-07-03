<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Service\RepositoryServiceable;
use Illuminate\Http\Request;


class PostsController extends Controller
{
    protected RepositoryServiceable $postsService;

    public function __construct(RepositoryServiceable $postsService)
    {
        $this->middleware('auth')->only(['create','update']);
        $this->middleware('bind.model.from.cache:post')->only(['show', 'edit', 'update', 'destroy']);
        $this->postsService = $postsService;
    }

    public function index()
    {
        $currentPage = request()->get('page',1);
        $posts = $this->postsService->publicIndex(cachedUser(), ['page' => $currentPage]);
        return view('posts.index', compact( 'posts'));
    }

    public function show(Request $request)
    {
       $post = $request->attributes->get('post');
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

    public function edit(Request $request)
    {
        $post = $request->attributes->get('post');
        $this->authorize('update', $post->model);

        $isAdmin = false;

        return view('posts.edit', compact('post', 'isAdmin'));
    }

    public function update(PostStoreAndUpdateRequest $request, $slug)
    {
        $post = $request->attributes->get('post');
        $this->authorize('update', $post->model);

        $this->postsService->update($request, $slug, cachedUser());

        return redirect()->route('posts.index');
    }

    public function destroy(Request $request)
    {
        $post = $request->attributes->get('post');
        $this->authorize('update', $post->model);

        $this->postsService->destroy($post->slug, cachedUser());

        return redirect()->route('posts.index');
    }
}
