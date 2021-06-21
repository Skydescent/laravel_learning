<?php

namespace App\Repositories;

use App\Feedback;
use App\Service\EloquentCacheService;
use App\Service\FeedbacksService;
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
        $this->cacheService = EloquentCacheService::getInstance(static::$model);
    }

    /**
     * @inheritDoc
     */
    protected function setModelService()
    {
        $this->modelService = new FeedbacksService();
    }

    public function adminIndex(Authenticatable|User|null $user = null, array $postfixes = []) : mixed
    {
        $collection = (self::$model)::latest()->get();
        return $this->cacheService->cacheCollection($collection, $user, $postfixes);
    }

}