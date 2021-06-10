<?php

namespace App\Repositories;

use App\Service\CacheService;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class TagEloquentRepository extends EloquentRepository
{

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