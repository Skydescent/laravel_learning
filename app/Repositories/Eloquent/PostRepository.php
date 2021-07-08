<?php

namespace App\Repositories\Eloquent;

class PostRepository extends  Repository
{
    /**
     * @param null $request
     * @return array
     */
    protected function prepareAttributes($request = null): array
    {
        $attributes = $request->validated();
        $attributes['published'] = $attributes['published'] ?? 0;
        $attributes['owner_id'] = isset($this->model->owner) ? $this->model->owner->id :cachedUser()->id;
        return $attributes;
    }
}