<?php

namespace App\Service\Eloquent;

use App\Repositories\Eloquent\TaskRepository;
use App\Models\Task;
use App\Models\User;

class TasksService extends Service
{
    public array $flashMessages = [
        'update' => 'Задача успешно обновлена!',
        'store' => 'Задача успешно создана!',
        'destroy' => [
            'message' => 'Задача удалена!',
            'type' => 'warning'
        ]
    ];


    protected function setModelClass()
    {
        $this->modelClass = Task::class;
    }


    protected function setRepository()
    {
        $this->repository = TaskRepository::getInstance($this->modelClass);
    }

    public function index(?User $user = null, array $postfixes = []): mixed
    {
        $getIndex = function () use ($user) {
            return $user->tasks()->with('tags')->latest()->get();
        };

        return $this->repository->index($getIndex, $this->getModelKeyName(), $user,$postfixes);
    }

}