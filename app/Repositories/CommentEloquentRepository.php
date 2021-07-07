<?php

namespace App\Repositories;

use App\Commentable;
use App\User;

class CommentEloquentRepository extends EloquentRepository
{

    protected function prepareAttributes($request = null) : array
    {
        $attributes = $request->validate([
            'body' => 'required',
        ]);
        $attributes['author_id'] = cachedUser()->id;

        return $attributes;
    }

    public function store($request, Commentable $relatedModel = null, ?User $user = null)
    {
        $attributes = $this->prepareAttributes($request);
        $this->cacheService->forgetMorphedModelRelation($relatedModel, ['relation' => 'comments'], $user);
        $this->cacheService->flushCollections();

        return $relatedModel->comments()->create($attributes);
    }
}