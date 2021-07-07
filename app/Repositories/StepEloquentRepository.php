<?php

namespace App\Repositories;

use App\Step;
use App\User;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StepEloquentRepository extends EloquentRepository
{

    /**
     * @return array
     */
    protected function prepareAttributes($request = null) : array
    {
        $attributes = $request->all();
        $attributes['completed'] = isset($attributes['completed']) && $attributes['completed'] === 'on';

        return $attributes;
    }

    public function update (FormRequest|Request $request, array $identifier, ?User $user = null, ?UrlRoutable $morphedModel = null,)
    {
        $this->cacheService->forgetMorphedModelRelation($morphedModel, ['relation' => 'steps'], $user);
        return parent::update($request, $identifier, $user);
    }
}