<?php

namespace App\Service\Eloquent;

use App\Repositories\Eloquent\TaskRepository;
use App\Models\Task;
use App\Models\User;

class TasksService extends Service
{

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

    public function store(array $attributes)
    {
        parent::store($attributes);
        flash('Задача успешно создана!');
    }

    public function update(array $attributes, string $identifier, ?User $user = null)
    {
        parent::update($attributes, $identifier, $user);
        flash('Задача успешно обновлена!');
    }

    public function destroy(string $identifier, ?User $user = null)
    {
        parent::destroy($identifier, $user);
        flash('Задача удалена!', 'warning');
    }


}