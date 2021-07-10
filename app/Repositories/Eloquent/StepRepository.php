<?php

namespace App\Repositories\Eloquent;

use App\Models\Stepable;
use App\Models\User;
use Illuminate\Contracts\Routing\UrlRoutable;

class StepRepository extends Repository
{

    public function store(array $attributes, Stepable $relatedModel = null, ?User $user = null) : mixed
    {
        $this->cacheService->forgetMorphedModelRelation($relatedModel, ['relation' => 'steps'], $user);
        $this->cacheService->flushCollections();

        return $relatedModel->addStep($attributes);
    }

    public function update (
        array $attributes,
        array $identifier,
        ?User $user = null,
        ?UrlRoutable $morphedModel = null,
    ) : mixed
    {
        $this->cacheService->forgetMorphedModelRelation($morphedModel, ['relation' => 'steps'], $user);
        return parent::update($attributes, $identifier, $user);
    }
}