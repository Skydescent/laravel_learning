<?php

namespace App\Repositories\Eloquent;

class TaskRepository extends Repository
{
    /**
     * @param $request
     * @return array
     */
    protected function prepareAttributes($request = null): array
    {
        $attributes = $request->validated();
        $attributes['owner_id'] = isset($this->model->owner) ? $this->model->owner->id :cachedUser()->id;
        return $attributes;
    }
}