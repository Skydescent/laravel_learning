<?php

namespace App\Http\Controllers;

use App\News;
use App\Repositories\EloquentRepositoryInterface;

class NewsCommentsController extends Controller
{
    protected EloquentRepositoryInterface $modelInterface;

    public function __construct(EloquentRepositoryInterface $modelInterface)
    {
        $this->modelInterface = $modelInterface;
    }

    public function store(News $news)
    {
        $this->modelInterface->store(\request(), $news);
        return back();
    }
}
