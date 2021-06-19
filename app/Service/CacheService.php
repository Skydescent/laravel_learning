<?php


namespace App\Service;


use App\Cache\CacheEloquentWrapper;
use App\User;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Collection;

class CacheService
{
    private static $instances = [];

    protected string $modelClass;

    public array $configs;

    protected function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->initialize();
    }

    protected function __clone()
    {

    }

    protected function initialize()
    {
        $allConfigs = config('cache.cache_service');
        $this->configs = array_diff_key($allConfigs, ['map' => '']);

        if (array_key_exists($this->modelClass,$allConfigs['map'])) {
            $this->configs = array_merge($this->configs, $allConfigs['map'][$this->modelClass]);
        }
    }

    public static function getInstance(string $modelClass): CacheService
    {
        if (!isset(self::$instances[$modelClass])) {
            self::$instances[$modelClass] = new static($modelClass);
        }

        return self::$instances[$modelClass];
    }

    public function cacheItem($data, $cacheKey, ?User $user = null)
    {
        $queryData = function () use ($cacheKey, $data) {
            return CacheEloquentWrapper::wrapItem($data, $cacheKey, $this);
        };

        return $this->cache($queryData, $user, $cacheKey);
    }


    public function cacheCollection(
        Collection|\Illuminate\Database\Eloquent\Collection $collection ,
        User $user = null,
        array $postfixes = [],
        array $tags = []
    )
    {
        $queryData = function () use ($collection) {
            return CacheEloquentWrapper::wrapCollection($collection, $this);
        };

        return $this->cache($queryData, $user, $postfixes, array_merge([$this->getTagName() . '_collection'], $tags));
    }


    public function cachePaginator(
        \Illuminate\Pagination\Paginator|\Illuminate\Pagination\LengthAwarePaginator $paginator,
        User $user = null,
        $postfixes = []
    )
    {
        $queryData = function () use ($paginator) {
            return CacheEloquentWrapper::wrapPaginator($paginator, $this);
        };

        return $this->cache($queryData, $user, $postfixes, [$this->getTagName() . '_collection']);
    }

    public function cache(callable $queryData , User $user = null, $postfixes = [], array|null $tags = null)
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

    public function getTagName()
    {
        return  $this->configs['tag'] ??  $this->modelClass;
    }


    public function getKeyName(User|null $user, $postfixes): string
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
            $this->forgetModelRelation($identifier, ['relation' => $relationName], $user, [$relationName . '_collection']);
        }
    }

    public function forgetMorphedModelRelation(UrlRoutable $model, array $relationName, User|null $user = null)
    {
        $morphedCacheService = static::getInstance(get_class($model));
        $identifier = $morphedCacheService->getModelIdentifier($model);
        $morphedCacheService->forgetModelRelation($identifier, $relationName, $user);
    }

    public function forgetModelRelation(array $identifier, array $relationName, User|null $user = null, array $tags = [])
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