<?php

namespace App\Http\Controllers;

use App\News;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class NewsController extends Controller
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        $news = News::latest()->where('published', 1)->get();
        return view('news.index', compact( 'news'));
    }

    /**
     * @param News $news
     * @return Application|Factory|View
     */
    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }
}
