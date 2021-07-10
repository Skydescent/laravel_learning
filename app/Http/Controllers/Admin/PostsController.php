<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\PostsController as BasePostController;
use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Service\AdminServiceable;
use App\Service\TagsInterface;
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
        $this->middleware('model.from.cache:' . get_class($postsService) . ',post');
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $currentPage = request()->get('page',1);
        $posts = $this->postsService->adminIndex(cachedUser(), ['page' => $currentPage]);
        return view('admin.posts.index', compact( 'posts'));
    }

    /**
     * @return Application|Factory|View
     * @throws AuthorizationException
     */
    public function edit(): View|Factory|Application
    {
        $post = \request()->attributes->get('post');
        $isAdmin = true;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    /**
     * @param PostStoreAndUpdateRequest $request
     * @param $slug
     * @return RedirectResponse
     */
    public function update(PostStoreAndUpdateRequest $request, $slug): RedirectResponse
    {
        $this
            ->tagsService
            ->updateWithTagsSync(
                $this->postsService,
                $this->prepareAttributes($request),
                $slug,
                cachedUser()
            );
        return redirect()->route('admin.posts.index');
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
