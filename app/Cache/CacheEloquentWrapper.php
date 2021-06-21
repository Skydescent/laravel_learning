<?php


namespace App\Cache;

use App\Service\EloquentCacheService;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use JsonSerializable;

class CacheEloquentWrapper implements UrlRoutable, \ArrayAccess, Arrayable, Jsonable, JsonSerializable
{

    protected array $modelCacheKey;

    protected $model;

    protected  EloquentCacheService $cacheService;

    protected static string $modelClass;

    public static function wrapItem($item, array $modelCacheKey, EloquentCacheService $cacheService)
    {
        if (is_null($item)) return null;

        $instance = new static();
        $instance->model = $item;
        static::$modelClass = get_class($item);
        $instance->modelCacheKey = $modelCacheKey;
        $instance->cacheService = $cacheService;

        return $instance;
    }

    public static function wrapCollection(
        Collection|EloquentCollection $collection,
        EloquentCacheService          $cacheService, string $modelIdentifier =  null
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
        Paginator|LengthAwarePaginator $paginator,
        EloquentCacheService           $cacheService,
        string                         $modelIdentifier = null
    )
    {
        $modelsCollection = static::wrapCollection($paginator->getCollection(), $cacheService, $modelIdentifier);
        return $paginator->setCollection($modelsCollection);
    }

    public function __get(string $name)
    {
        if (in_array($name, $this->cacheService->getRelationsNames())) {

            $queryData = function () use ($name) {
                if (get_class($instance = $this->model->$name) === EloquentCollection::class) {
                    return static::wrapCollection($instance, $this->cacheService);
                } elseif (is_subclass_of($instance, Model::class)) {
                    $modelIdentifier = $instance->getRouteKeyName();
                    return static::wrapItem($instance,[$modelIdentifier => $instance->$modelIdentifier], $this->cacheService);
                }
            };
            return $this->cacheService->cache($queryData,auth()->user(), array_merge($this->modelCacheKey, ['relation' => $name]), [$name . '_collection']);
        }

        if($name === 'model') {
            return $this->getModel();
        }

        return $this->model->$name;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function __call(string $name, array $arguments)
    {
        return $this->model->{$name}($arguments);
    }

    public function __toString()
    {
        return json_encode($this->model);
    }

    public function toArray(): array
    {
        return $this->model->toArray();
    }

    public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }

    public function jsonSerialize ()
    {
        return $this->model->toArray();
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
     * @return Model|null
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
     * @return Model|null
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
       return $this->model->offsetExists($offset);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->model->offsetGet($offset);
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        return $this->model->offsetSet($offset, $value);
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        return $this->model->offsetSet($offset);
    }

}