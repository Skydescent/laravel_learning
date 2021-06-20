<?php

namespace App\Repositories;

use App\Feedback;
use App\Service\CacheService;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class FeedbackEloquentRepository extends EloquentRepository implements TaggableInterface
{

    use HasTags;

    /**
     * @inheritDoc
     */
    protected static function setModel()
    {
        static::$model = Feedback::class;
    }

    /**
     * @inheritDoc
     */
    protected function setCacheService()
    {
        $this->cacheService = CacheService::getInstance(static::$model);
    }

    /**
     * @inheritDoc
     */
    protected function setModelService()
    {
        $this->modelService = new \App\Service\FeedbacksService();
    }

    public function adminIndex(Authenticatable|User|null $user = null, array $postfixes = []) : mixed
    {
        $collection = (self::$model)::latest()->get();
        return $this->cacheService->cacheCollection($collection, $user, $postfixes);
    }

}