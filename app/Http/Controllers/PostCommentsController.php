<?php

namespace App\Http\Controllers;

use App\Post;
use App\Service\CommentsService;

class PostCommentsController extends Controller
{
    private $commentsService;

    public function __construct(CommentsService $commentsService)
    {
        $this->commentsService = $commentsService;
    }

    public function store(Post $post)
    {
        $this->commentsService->store($post, \request());

        return back();
    }
}
