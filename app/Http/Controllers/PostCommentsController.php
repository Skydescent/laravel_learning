<?php

namespace App\Http\Controllers;

use App\Post;
use App\Repositories\CommentableInterface;

class PostCommentsController extends Controller
{
    protected CommentableInterface $modelRepositoryInterface;

    public function __construct(CommentableInterface $modelRepositoryInterface)
    {
        $this->modelRepositoryInterface = $modelRepositoryInterface;
    }

    public function store(Post $post)
    {
        $this->modelRepositoryInterface->storeComment(\request(), $post);
        return back();
    }
}
