<?php

namespace App\Http\Controllers;

use App\Contracts\Repository\RepositoryCommentableContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

abstract class CommentsController extends Controller
{
    private RepositoryCommentableContract $commentableRepository;

    public function __construct(RepositoryCommentableContract $commentableRepository)
    {
        $this->commentableRepository = $commentableRepository;
    }

    public function store(Request $request, $slug): RedirectResponse
    {
        $attributes = $request->validate(['body' => 'required']);
        $attributes['author_id'] = getUserId();
        $this->commentableRepository->addComment($attributes, $slug);

        return back();
    }
}