<?php

namespace App\Service;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class SimpleCacheService extends CacheService
{

    protected static function setConfigsMap()
    {
        static::$configsMap = 'simple_services';
    }

    protected function getKeyName(User|Authenticatable $user = null, $postfixes = []): string
    {
       return '|' . $this->getTagName() . '|';
    }

    protected function getCachedModelsCollectionsTags()
    {
        if (
            isset($this->configs['cached_models_collections']) &&
            \config('cache.cache_service.map')
        ) {
            //TODO: найти пересечение массивов, пройти по нему и получить теги моделей
            // далее преобразовать из в тэг_collection и выдать массив данных тегов
        }

    }
}