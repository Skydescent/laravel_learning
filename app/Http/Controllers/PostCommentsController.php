<?php

namespace App\Http\Controllers;

use App\Post;
use App\Repositories\CommentableInerface;

class PostCommentsController extends Controller
{
    protected CommentableInerface $modelInterface;

    public function __construct(CommentableInerface $modelInterface)
    {
        $this->modelInterface = $modelInterface;
    }

    public function store(Post $post)
    {
        $this->modelInterface->storeComment(\request(), $post);
        return back();
    }
}
