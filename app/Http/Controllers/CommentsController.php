<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\RedirectResponse;

class CommentsController extends Controller
{
    /**
     * @param Post $post
     * @return RedirectResponse
     */
    public function store(Post $post) : RedirectResponse
    {
        // TODO: add author to attributes - may be array merge, or Eloquent attach use
        // TODO: add comments factory to test comments
        // TODO: may be add some tests to comments
        $post->addComment( \request()->validate([
            'body' => 'required'
        ]));

        return back();
    }
}
