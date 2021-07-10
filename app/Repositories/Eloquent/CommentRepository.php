<?php

namespace App\Repositories\Eloquent;

use App\Models\Commentable;
use App\Models\User;

class CommentRepository extends Repository
{

    public function store(array $attributes, Commentable $relatedModel = null, ?User $user = null) : mixed
    {
        $this->cacheService->forgetMorphedModelRelation($relatedModel, ['relation' => 'comments'], $user);
        $this->cacheService->flushCollections();

        return $relatedModel->comments()->create($attributes);
    }
}