<?php

namespace App\Service;

use App\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TasksService extends EloquentService
{
    public array $flashMessages = [
        'update' => 'Задача успешно обновлена!',
        'store' => 'Задача успешно создана!',
        'destroy' => ['message' => 'Задача удалена!', 'type' => 'warning']
    ];


    protected static function setModelClass()
    {
        static::$modelClass = Task::class;
    }

    /**
     * @param $request
     * @return array
     */
    protected function prepareAttributes($request = null): array
    {
        $attributes = $request->validated();
        $attributes['owner_id'] = isset($this->model->owner) ? $this->model->owner->id :auth()->id();
        return $attributes;
    }

}