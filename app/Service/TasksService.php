<?php

namespace App\Service;

use App\Repositories\TaskEloquentRepository;
use App\Task;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class TasksService extends EloquentService
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
        $this->repository = TaskEloquentRepository::getInstance($this->modelClass);
    }

    public function index(?User $user = null, array $postfixes = []): mixed
    {
        $getIndex = function () use ($user) {
            return $user->tasks()->with('tags')->latest()->get();
        };

        return $this->repository->index($getIndex, $this->getModelKeyName(), $user,$postfixes);
    }

}