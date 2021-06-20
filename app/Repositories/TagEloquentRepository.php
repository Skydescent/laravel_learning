<?php

namespace App\Repositories;

use App\Service\EloquentCacheService;

class TagEloquentRepository extends EloquentRepository implements TaggableInterface
{
    use HasTags;

    /**
     * @inheritDoc
     */
    protected static function setModel()
    {
        static::$model = \App\Tag::class;
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
        $this->modelService = new \App\Service\TagService();
    }
}