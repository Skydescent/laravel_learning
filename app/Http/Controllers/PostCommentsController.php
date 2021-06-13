<?php

namespace App\Http\Controllers;

use App\Post;
use App\Repositories\EloquentRepositoryInterface;

class PostCommentsController extends Controller
{
    protected EloquentRepositoryInterface $modelInterface;

    public function __construct(EloquentRepositoryInterface $modelInterface)
    {
        $this->modelInterface = $modelInterface;
    }

    public function store(Post $post)
    {
        $this->modelInterface->store(\request(), $post);
        return back();
    }
}
