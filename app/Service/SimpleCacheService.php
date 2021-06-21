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

    protected function getCachedModelsCollectionsTags():array
    {
        $tags = [];
        if (
            isset($this->configs['cached_models_collections']) &&
            isset($this->configs['map'])
        ) {
            $map = $this->configs['map'];
            $cachedModelsInMap = array_intersect(
                $this->configs['cached_models_collections'],
                array_keys($map)
            );
            foreach ($cachedModelsInMap as $modelName) {
                $tags[] = $map[$modelName]['tag'] . '_collection';
            }
        }

        return $tags;
    }

    public function cacheQueryData(callable $queryData)
    {
        $tags = $this->getCachedModelsCollectionsTags();
        return $this->cache($queryData, null, [], $tags);
    }
}