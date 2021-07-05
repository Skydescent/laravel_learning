<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Service\RepositoryServiceable;
use Illuminate\Auth\Access\AuthorizationException;
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
        $this->middleware('auth')->only(['create','update']);
        $this
            ->middleware('model.from.cache:' . get_class($postsService) . ',post')
            ->only(['show', 'edit', 'update', 'destroy']);
        $this->postsService = $postsService;
    }

    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $currentPage = request()->get('page',1);
        $posts = $this->postsService->publicIndex(cachedUser(), ['page' => $currentPage]);
        return view('posts.index', compact( 'posts'));
    }

    /**
     * @param Request $request
     * @return Factory|View|Application
     */
    public function show(Request $request): Factory|View|Application
    {
       $post = $request->attributes->get('post');
        return view('posts.show', compact('post'));
    }

    /**
     * @return Factory|View|Application
     */
    public function create(): Factory|View|Application
    {
        return view('posts.create');
    }

    /**
     * @param PostStoreAndUpdateRequest $request
     * @return RedirectResponse
     */
    public function store(PostStoreAndUpdateRequest $request): RedirectResponse
    {
        $this->postsService->store($request);

        return redirect()->route('posts.index');
    }

    /**
     * @param Request $request
     * @return Factory|View|Application
     * @throws AuthorizationException
     */
    public function edit(Request $request): Factory|View|Application
    {
        $post = $request->attributes->get('post');
        $this->authorize('update', $post->model);

        $isAdmin = false;

        return view('posts.edit', compact('post', 'isAdmin'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(PostStoreAndUpdateRequest $request, string $slug): RedirectResponse
    {
        $post = $request->attributes->get('post');
        $this->authorize('update', $post->model);

        $this->postsService->update($request, $slug, cachedUser());

        return redirect()->route('posts.index');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request): RedirectResponse
    {
        $post = $request->attributes->get('post');
        $this->authorize('update', $post->model);

        $this->postsService->destroy($post->slug, cachedUser());

        return redirect()->route('posts.index');
    }
}
