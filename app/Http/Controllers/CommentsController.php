<?php

namespace App\Http\Controllers;

use App\Service\CommentsInterface;
use App\Service\Serviceable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
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

    protected function prepareAttributes(FormRequest|Request $request): array
    {
        $attributes = $request->validate([
            'body' => 'required',
        ]);
        $attributes['author_id'] = cachedUser()->id;

        return $attributes;
    }

    public function store(Request $request, $id): RedirectResponse
    {
        $user = cachedUser();
        $post = $this
            ->postsService
            ->find($id)
            ->model;

        $this->commentsService->storeComment($this->prepareAttributes($request), $post, $user);

        return back();
    }
}