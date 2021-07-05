<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Post;
use App\Repositories\EloquentRepositoryInterface;
use App\Service\RepositoryServiceable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    /**
     * @var RepositoryServiceable
     */
    protected RepositoryServiceable $postsService;

    /**
     * @param RepositoryServiceable $postsService
     */
    public function __construct(RepositoryServiceable $postsService)
    {
        $this->middleware('model.from.cache:' . get_class($postsService) . ',post');
        $this->postsService = $postsService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $currentPage = request()->get('page',1);
        $posts = $this->postsService->adminIndex(cachedUser(), ['page' => $currentPage] );
        return view('admin.posts.index', compact( 'posts'));
    }

    /**
     * @param PostStoreAndUpdateRequest $request
     * @param $slug
     * @return RedirectResponse
     */
    public function update(PostStoreAndUpdateRequest $request, $slug): RedirectResponse
    {
        $this->postsService->update($request, $slug, cachedUser());
        return redirect()->route('admin.posts.index');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function edit(Request $request): View|Factory|Application
    {
        $post = $request->attributes->get('post');
        $isAdmin = true;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    /**
     * @param string $slug
     * @return RedirectResponse
     */
    public function destroy(string $slug): RedirectResponse
    {
        $this->postsService->destroy($slug, cachedUser());
        return redirect()->route('admin.posts.index');
    }
}
