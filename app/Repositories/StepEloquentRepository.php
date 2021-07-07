<?php

namespace App\Repositories;

use App\Step;
use App\Stepable;
use App\User;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StepEloquentRepository extends EloquentRepository
{

    /**
     * @param null $request
     * @return array
     */
    protected function prepareAttributes($request = null) : array
    {
        if ($request->get('description') !== null) {
            $request->validate([
                'description' => 'required|min:5'
            ]);
        }
        $attributes = $request->all();
        $attributes['completed'] = isset($attributes['completed']) && $attributes['completed'] === 'on';

        return $attributes;
    }

    public function store($request, Stepable $relatedModel = null, ?User $user = null)
    {
        $attributes = $this->prepareAttributes($request);
        $this->cacheService->forgetMorphedModelRelation($relatedModel, ['relation' => 'steps'], $user);
        $this->cacheService->flushCollections();

        return $relatedModel->addStep($attributes);
    }

    public function update (FormRequest|Request $request, array $identifier, ?User $user = null, ?UrlRoutable $morphedModel = null,)
    {
        $this->cacheService->forgetMorphedModelRelation($morphedModel, ['relation' => 'steps'], $user);
        return parent::update($request, $identifier, $user);
    }
}