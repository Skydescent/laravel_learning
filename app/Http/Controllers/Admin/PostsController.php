<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Post;
use App\Repositories\EloquentRepositoryInterface;
use App\Service\RepositoryServiceable;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    protected RepositoryServiceable $postsService;

    public function __construct(RepositoryServiceable $postsService)
    {
        $this->middleware('bind.model.from.cache:post');
        $this->postsService = $postsService;
    }

    public function index()
    {
        $currentPage = request()->get('page',1);
        $posts = $this->postsService->adminIndex(cachedUser(), ['page' => $currentPage] );
        return view('admin.posts.index', compact( 'posts'));
    }

    public function update(PostStoreAndUpdateRequest $request, $slug)
    {
        $this->postsService->update($request, $slug, cachedUser());
        return redirect()->route('admin.posts.index');
    }

    public function edit(Request $request)
    {
        $post = $request->attributes->get('post');
        $isAdmin = true;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    public function destroy($slug)
    {
        $this->postsService->destroy($slug, cachedUser());
        return redirect()->route('admin.posts.index');
    }
}
