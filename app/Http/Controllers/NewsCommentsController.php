<?php

namespace App\Http\Controllers;

use App\News;
use App\Service\CommentsService;

class NewsCommentsController extends Controller
{
    private $commentsService;

    public function __construct(CommentsService $commentsService)
    {
        $this->commentsService = $commentsService;
    }

    public function store(News $news)
    {
        $this->commentsService->store($news, \request());
        return back();
    }
}
