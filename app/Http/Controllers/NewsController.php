<?php

namespace App\Http\Controllers;

use App\Service\RepositoryServiceable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class NewsController extends Controller
{

    /**
     * @var RepositoryServiceable
     */
    protected RepositoryServiceable $newsService;

    /**
     * @param RepositoryServiceable $newsService
     */
    public function __construct(RepositoryServiceable $newsService)
    {
        $this
            ->middleware('model.from.cache:' . get_class($newsService) . ',news')
            ->only(['show']);
        $this->newsService = $newsService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $currentPage = request()->get('page',1);
        $news = $this->newsService->publicIndex(cachedUser(), ['page' => $currentPage]);
        return view('news.index', compact( 'news'));
    }


    /**
     * @param Request $request
     * @return Application|Factory|View
     */
    public function show(Request $request): View|Factory|Application
    {
        $news = $request->attributes->get('news');
        return view('news.show', compact('news'));
    }
}
