<?php

namespace App\Repositories;

use App\Service\EloquentCacheService;
use App\Service\TasksService;
use App\Task;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class TaskEloquentRepository extends EloquentRepository implements TaggableInterface, StepableInterface
{
    use HasTags, HasSteps;

    /**
     * @inheritDoc
     */
    protected static function setModel()
    {
        static::$model = Task::class;
    }

    /**
     * @inheritDoc
     */
    protected function setCacheService()
    {
        $this->cacheService = EloquentCacheService::getInstance(static::$model);
    }

    /**
     * @inheritDoc
     */
    protected function setModelService()
    {
        $this->modelService = new TasksService();
    }

    public function publicIndex(User|Authenticatable|null $user = null, array $postfixes = []): mixed
    {
        $collection = $user->tasks()->with('tags')->latest()->get();

        return $this->cacheService->cacheCollection($collection, $user, $postfixes);
    }

}