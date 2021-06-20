<?php

namespace App\Http\Controllers;

use App\News;
use App\Repositories\CommentableInerface;
use App\Repositories\EloquentRepositoryInterface;

class NewsCommentsController extends Controller
{
    protected CommentableInerface $modelInterface;

    public function __construct(CommentableInerface $modelInterface)
    {
        $this->modelInterface = $modelInterface;
    }

    public function store(News $news)
    {
        $this->modelInterface->storeComment(\request(), $news);
        return back();
    }
}
