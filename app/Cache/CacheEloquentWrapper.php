<?php


namespace App\Cache;


use Illuminate\Pagination\Paginator;

class CacheEloquentWrapper implements \Illuminate\Contracts\Routing\UrlRoutable, \ArrayAccess
{

    protected array $modelCacheKey;

    protected $model;

    protected  \App\Service\CacheService $cacheService;

    protected static $modelClass;

    public static function wrapItem($item, array $modelCacheKey, \App\Service\CacheService $cacheService)
    {
        $instance = new static();
        $instance->model = $item;
        static::$modelClass = get_class($item);
        $instance->modelCacheKey = $modelCacheKey;
        $instance->cacheService = $cacheService;

        return $instance;
    }

    public static function wrapCollection(
        \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection $collection,
        \App\Service\CacheService $cacheService, string $modelIdentifier =  null
    )
    {
        if($collection->first()) {
            $modelIdentifier = $modelIdentifier ?? $collection->first()->getRouteKeyName();
            return $collection
                ->map(function ($model) use ($modelIdentifier, $cacheService) {
                    return static::wrapItem($model, [$modelIdentifier => $model->$modelIdentifier], $cacheService);
                });
        }
       return collect();
    }

    public static function wrapPaginator(
        \Illuminate\Pagination\Paginator $paginator,
        \App\Service\CacheService $cacheService,
        string $modelIdentifier = null
    )
    {
        $modelsCollection = static::wrapCollection($paginator->getCollection(), $cacheService, $modelIdentifier);
        return $paginator->setCollection($modelsCollection);
    }

    public function __get(string $name)
    {
        if (in_array($name, array_keys($this->cacheService->configs['relations']))) {
            $queryData = function () use ($name) {

                if (get_class($instance = $this->model->$name) === \Illuminate\Database\Eloquent\Collection::class) {
                    return static::wrapCollection($instance, $this->cacheService);
                } elseif (is_subclass_of($instance, \Illuminate\Database\Eloquent\Model::class)) {
                    $modelIdentifier = $instance->getRouteKeyName();
                    return static::wrapItem($instance,[$modelIdentifier => $instance->$modelIdentifier], $this->cacheService);
                }
            };
            return $this->cacheService->cache($queryData,null, array_merge($this->modelCacheKey, ['relation' => $name]));
        }

        return $this->model->$name;
    }

    public function __call(string $name, array $arguments)
    {
        return $this->model->{$name}($arguments);
    }

    public function __toString()
    {
        return $this->model;
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->model->getRouteKey();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return $this->model->getRouteKeyName();
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param string|null $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->model->resolveRouteBinding($value, $field);
    }

    /**
     * Retrieve the child model for a bound value.
     *
     * @param string $childType
     * @param mixed $value
     * @param string|null $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        $this->model->resolveChildRouteBinding($childType, $value, $field);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

}