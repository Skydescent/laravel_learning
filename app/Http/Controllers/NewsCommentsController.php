<?php

namespace App\Http\Controllers;

use App\News;

class NewsCommentsController extends Controller
{
    public function store(News $news)
    {
        $this->modelInterface->store(\request(), $news);
        return back();
    }
}
