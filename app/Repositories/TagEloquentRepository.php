<?php

namespace App\Repositories;

use App\Service\EloquentCacheService;
use App\Service\TagService;
use App\Tag;

class TagEloquentRepository extends EloquentRepository implements TaggableInterface
{
    use HasTags;

    /**
     * @inheritDoc
     */
    protected static function setModel()
    {
        static::$model = Tag::class;
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
        $this->modelService = new TagService();
    }
}