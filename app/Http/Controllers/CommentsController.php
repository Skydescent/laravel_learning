<?php

namespace App\Http\Controllers;

use App\Service\CommentsInterface;
use App\Service\Serviceable;
use Illuminate\Http\Request;

abstract class CommentsController extends Controller
{
    protected CommentsInterface $commentsService;

    protected Serviceable $postsService;

    public function __construct(CommentsInterface $commentsService, Serviceable $postsService)
    {
        $this->commentsService = $commentsService;
        $this->postsService = $postsService;
    }

    public function store(Request $request, $id)
    {
        $user = cachedUser();
        $post = $this
            ->postsService
            ->find($id)
            ->model;

        $this->commentsService->storeComment($request, $post, $user);

        return back();
    }
}