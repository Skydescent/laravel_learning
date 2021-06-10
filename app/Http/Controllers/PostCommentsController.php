<?php

namespace App\Http\Controllers;

use App\Post;

class PostCommentsController extends Controller
{
    public function store(Post $post)
    {
        $this->modelInterface->store(\request(), $post);
        return back();
    }
}
