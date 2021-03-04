<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\RedirectResponse;

class CommentsController extends Controller
{
    /**
     * CommentsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
    }

    /**
     * @param Post $post
     * @return RedirectResponse
     */
    public function store(Post $post) : RedirectResponse
    {
        $attributes = \request()->validate([
           'body' => 'required',
        ]);
        $attributes['author_id'] = auth()->id();

        $post->addComment($attributes);

        return back();
    }
}
