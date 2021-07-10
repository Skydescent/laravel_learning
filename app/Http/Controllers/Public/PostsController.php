<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\PostsController as BasePostController;
use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Service\AdminServiceable;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;


class PostsController extends BasePostController
{
    public function __construct(AdminServiceable $postsService)
    {
        parent::__construct($postsService);
        $this->middleware('auth')->only(['create','update']);
        $this
            ->middleware('model.from.cache:' . get_class($postsService) . ',post')
            ->only(['show', 'edit', 'update', 'destroy']);
    }

    /**
     * @return Factory|View|Application
     */
    public function index(): Factory|View|Application
    {
        $currentPage = request()->get('page',1);
        $user = cachedUser();
        $posts = $this->postsService->index($user, ['page' => $currentPage]);

        return view('posts.index', compact( 'posts', 'user'));
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
        $this
            ->tagsService
            ->storeWithTagsSync(
                $this->postsService,
                $this->prepareAttributes($request)
            );

        return redirect()->route('posts.index');
    }

    /**
     * @return Factory|View|Application
     * @throws AuthorizationException
     */
    public function edit(): Factory|View|Application
    {
        $post = \request()->attributes->get('post');
        $this->authorizeIfNeeded('update', $post->model);
        $isAdmin = false;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(PostStoreAndUpdateRequest $request): RedirectResponse
    {
        $attributes = $this->prepareAttributes($request);
        $post = $request->attributes->get('post');
        $this->authorizeIfNeeded('update', $post->model);

        $this
            ->tagsService
            ->updateWithTagsSync(
                $this->postsService,
                $attributes,
                $post->slug,
                cachedUser()
            );

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
        $this->authorizeIfNeeded('update', $post->model);

        $this->postsService->destroy($post->slug, cachedUser());

        return redirect()->route('posts.index');
    }
}
