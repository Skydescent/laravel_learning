<?php

namespace App\Repositories;

use App\Service\CacheService;

class TagEloquentRepository extends EloquentRepository implements RepositoryTaggableInterface
{
    use HasTags;

    //TODO: Remove class?

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
        $this->cacheService = CacheService::getInstance(static::$model);
    }

    /**
     * @inheritDoc
     */
    protected function setModelService()
    {
        $this->modelService = new \App\Service\TagService();
    }
}