<?php

namespace App\Http\Controllers;

use App\News;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class NewsController extends Controller
{

    protected EloquentRepositoryInterface $modelRepositoryInterface;

    public function __construct(EloquentRepositoryInterface $modelRepositoryInterface)
    {
        $this->modelRepositoryInterface = $modelRepositoryInterface;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $currentPage = request()->get('page',1);
        $news = $this->modelRepositoryInterface->publicIndex(auth()->user(), ['page' => $currentPage]);
        return view('news.index', compact( 'news'));
    }

    /**
     * @param News $news
     * @return Application|Factory|View
     */
    public function show(News $news): View|Factory|Application
    {
        $news = $this->modelRepositoryInterface->find($news, auth()->user());
        return view('news.show', compact('news'));
    }
}
