<?php


namespace App\Service;


use App\Commentable;

class CommentsService
{
    public function store(Commentable $model, $request)
    {
        $attributes = $request->validate([
            'body' => 'required',
        ]);
        $attributes['author_id'] = auth()->id();

        $model->comments()->create($attributes);
    }
}