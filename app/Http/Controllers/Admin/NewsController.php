<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsStoreAndUpdateRequest;
use App\Models\News;
use App\Service\AdminServiceable;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    /**
     * @var AdminServiceable
     */
    protected AdminServiceable $newsService;

    /**
     * @param AdminServiceable $newsService
     */
    public function __construct(AdminServiceable $newsService)
    {
        $this
            ->middleware('model.from.cache:' . get_class($newsService) . ',news')
            ->only(['edit', 'update', 'destroy']);
        $this->newsService = $newsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(): View
    {
        $currentPage = request()->get('page',1);
        $news = $this->newsService->adminIndex(cachedUser(), ['page' => $currentPage]);

        return view('admin.news.index', compact( 'news'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        $news = new News();
        return view('admin.news.create', compact('news'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  NewsStoreAndUpdateRequest $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(NewsStoreAndUpdateRequest $request) : RedirectResponse
    {
        $this->newsService->store($request);
        return redirect()->route('admin.news.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Request $request
     * @return View
     */
    public function edit(Request $request) : View
    {
        $news = $request->attributes->get('news');
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  NewsStoreAndUpdateRequest  $request
     * @param  string $slug
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(NewsStoreAndUpdateRequest $request, string $slug): RedirectResponse
    {
        $this->newsService->update($request, $slug, cachedUser());
        return redirect()->route('admin.news.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(Request $request): RedirectResponse
    {
        $this->newsService->destroy(
            $request
                ->attributes
                ->get('news')->slug,
            cachedUser());
        return redirect()->route('admin.news.index');
    }
}
