<?php

namespace App\Repositories;

use App\Service\EloquentCacheService;
use App\Service\TasksService;
use App\Task;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class TaskEloquentRepository extends EloquentRepository
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