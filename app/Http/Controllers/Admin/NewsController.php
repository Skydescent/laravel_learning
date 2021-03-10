<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\NewsStoreAndUpdateRequest;
use App\News;
use App\Service\NewsService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NewsController extends Controller
{
    private $newsService;

    public function __construct(NewsService $newsService)
    {
        $this->newsService = $newsService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index()
    {
        $news = News::latest()->paginate(20);

        return view('admin.news.index', compact( 'news'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create()
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
        $this->newsService
            ->setNews(new News())
            ->storeOrUpdate($request->validated());
        return redirect()->route('admin.news.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  News  $news
     * @return View
     */
    public function edit(News $news) : View
    {
        return view('admin.news.edit', compact('news'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  NewsStoreAndUpdateRequest  $request
     * @param  News $news
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(NewsStoreAndUpdateRequest $request, News $news)
    {
        $this->newsService
            ->setNews($news)
            ->storeOrUpdate($request->validated());
        return redirect()->route('admin.news.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param News $news
     * @return RedirectResponse
     * @throws Exception
     */
    public function destroy(News $news)
    {
        $news->delete();
        return redirect()->route('admin.news.index');
    }
}
