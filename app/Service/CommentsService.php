<?php


namespace App\Service;


class CommentsService
{
    public function store($model, $request)
    {
        $attributes = $request->validate([
            'body' => 'required',
        ]);
        $attributes['author_id'] = auth()->id();

        $model->comments()->create($attributes);
    }
}