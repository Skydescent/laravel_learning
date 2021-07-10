<?php

namespace App\Http\Controllers\Public;

use App\Service\AdminServiceable;
use App\Http\Controllers\NewsController as BaseNewsController;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class NewsController extends BaseNewsController
{
    public function __construct(AdminServiceable $newsService)
    {
        parent::__construct($newsService);
        $this
            ->middleware('model.from.cache:' . get_class($newsService) . ',news')
            ->only(['show']);
    }


    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $currentPage = request()->get('page',1);
        $news = $this->newsService->index(cachedUser(), ['page' => $currentPage]);
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
