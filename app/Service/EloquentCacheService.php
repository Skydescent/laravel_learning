<?php


namespace App\Service;

use App\Cache\CacheEloquentWrapper;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class EloquentCacheService extends CacheService
{
    /**
     */
    protected static function setConfigsMap()
    {
        static::$configsMap = 'map';
    }

    protected function __clone()
    {

    }

    public function cacheItem($data, $cacheKey, ?User $user = null)
    {
        $queryData = function () use ($cacheKey, $data) {
            return CacheEloquentWrapper::wrapItem($data, $cacheKey, $this);
        };

        return $this->cache($queryData, $user, $cacheKey);
    }


    public function cacheCollection(
        Collection|EloquentCollection $collection ,
        User                          $user = null,
        array                         $postfixes = [],
        array                         $tags = []
    )
    {
        $queryData = function () use ($collection) {
            return CacheEloquentWrapper::wrapCollection($collection, $this);
        };

        return $this->cache(
            $queryData,
            $user,
            $postfixes,
            array_merge([$this->getTagName() . '_collection'],
                $tags)
        );
    }


    public function cachePaginator(
        Paginator|LengthAwarePaginator $paginator,
        User                           $user = null,
                                       $postfixes = []
    )
    {
        $queryData = function () use ($paginator) {
            return CacheEloquentWrapper::wrapPaginator($paginator, $this);
        };

        return $this->cache($queryData, $user, $postfixes, [$this->getTagName() . '_collection']);
    }

    public function cache(
        callable             $queryData ,
        Authenticatable|User $user = null,
                             $postfixes = [],
        array|null           $tags = null
    )
    {
        $tags = $tags ??  [$this->getTagName()];
        $key = $this->getKeyName($user, $postfixes);

        return \Cache::tags($tags)
            ->remember(
                $key,
                $this->configs['ttl'],
                $queryData
            );
    }


    public function getKeyName(Authenticatable|User $user = null, $postfixes = [] ): string
    {
        $prefix = $this->configs['allPrefix'] . '_';
        $postfix = '';
        if ($user && $this->configs['isPersonal']) {
            $prefix = '';
            $postfix = '|' . $this->configs['personalKeyPrefix'] . '=' .  $user->id;
        }

        if (!empty($postfixes)) {
            foreach ($postfixes as $key => $value) {
                $postfix .= '|' . $key . '=' . $value;
            }
        }

        return $prefix . $this->getTagName() . $postfix;
    }

    public function flushModelCache(UrlRoutable $modelInstance, User|null $user = null)
    {
        $identifier = $this->getModelIdentifier($modelInstance);
        $this->forgetModel($identifier, $user);
        $this->forgetModelRelations($identifier, $user);
        $this->flushCollections();
    }

    public function forgetModel(array $identifier, User|null $user = null)
    {
        $keyName = $this->getKeyName($user, $identifier);
        $tag = $this->getTagName();

        \Cache::tags([$tag])->forget($keyName);
    }

    public function forgetModelRelations(array $identifier = null, User|null $user = null)
    {
        foreach ($this->getRelationsNames() as $relationName) {
            $this->forgetModelRelation(
                $identifier,
                ['relation' => $relationName],
                $user,
                [$relationName . '_collection']
            );
        }
    }

    public function forgetMorphedModelRelation(
        UrlRoutable               $model,
        array                     $relationName,
        Authenticatable|User|null $user = null
    )
    {
        $morphedCacheService = static::getInstance(get_class($model));
        $identifier = $morphedCacheService->getModelIdentifier($model);
        $tags = [$relationName['relation'] . '_collection'];
        $morphedCacheService->forgetModelRelation($identifier, $relationName, $user, $tags);
    }

    public function forgetModelRelation(
        array                     $identifier,
        array                     $relationName,
        Authenticatable|User|null $user = null,
        array                     $tags = []
    )
    {
        $postfixes = array_merge( $identifier, $relationName);
        $keyName = $this->getKeyName($user, $postfixes);

        $tags = count($tags) !== 0 ? $tags : [$this->getTagName()];

        \Cache::tags($tags)->forget($keyName);
    }

    public function flushCollections()
    {
        \Cache::tags([$this->getTagName() . '_collection'])->flush();

    }

    public function getRelationsNames(): array
    {
        return isset($this->configs['relations']) ? array_keys($this->configs['relations']) : [];
    }

    public function getModelIdentifier(UrlRoutable $modelInstance) : array
    {
        $routeKeyName = $modelInstance->getRouteKeyName();
        $instanceKeyName = $modelInstance->$routeKeyName;
        return [$routeKeyName => $instanceKeyName];
    }

}