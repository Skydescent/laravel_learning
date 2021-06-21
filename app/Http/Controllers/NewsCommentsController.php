<?php

namespace App\Http\Controllers;

use App\News;
use App\Repositories\CommentableInterface;

class NewsCommentsController extends Controller
{
    protected CommentableInterface $modelRepositoryInterface;

    public function __construct(CommentableInterface $modelRepositoryInterface)
    {
        $this->modelRepositoryInterface = $modelRepositoryInterface;
    }

    public function store(News $news)
    {
        $this->modelRepositoryInterface->storeComment(\request(), $news);
        return back();
    }
}
