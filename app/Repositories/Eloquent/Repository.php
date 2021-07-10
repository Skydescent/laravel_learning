<?php


namespace App\Repositories\Eloquent;

use App\Repositories\EloquentRepositoryInterface;
use App\Service\Eloquent\CacheService;
use App\Models\Taggable;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;

abstract  class Repository implements EloquentRepositoryInterface
{
    protected string $modelClass;

    /**
     * @var CacheService
     */
    protected CacheService $cacheService;

    /**
     * @var array
     */
    protected static array $instances = [];
    
    protected function __construct($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    protected function setCacheService($cacheService)
    {
        $this->cacheService = $cacheService;
    }
    
    protected function __clone()
    {

    }

    /**
     * @throws Exception
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize a singleton.");
    }


    /**
     * @return EloquentRepositoryInterface
     */
    public static function getInstance(string $modelClass): EloquentRepositoryInterface
    {
        if (!isset(static::$instances[$modelClass])) {
            static::$instances[$modelClass] = new static($modelClass);
        }
        static::$instances[$modelClass]->setCacheService(CacheService::getInstance($modelClass));
        return static::$instances[$modelClass];
    }

    public function index(callable $getIndex, string|null $modelKeyName = null, ?User $user = null, array $postfixes = [])
    {
        return $this->cacheService->cacheIndex($getIndex,$user,$postfixes, $modelKeyName);
    }

    /**
     * @param callable $getModel
     * @param array $identifier
     * @param User|null $user
     * @return mixed
     */
    public function find(callable $getModel, array $identifier, ?User $user = null): mixed
    {
        return  $this->cacheService->cacheModel($getModel, $identifier, $user);
    }

    /**
     * @param array $attributes
     * @return Taggable|mixed
     */
    public function store(array $attributes): mixed
    {
        $model = $this->storeOrUpdate($attributes);
        $this->cacheService->flushCollections();

        return $model;
    }

    public function createMany ($records) : Collection
    {
        $models = [];
        foreach($records as $record) {
            $models[] = ($this->modelClass)::create($record);
        }
        $this->cacheService->flushCollections();

       return collect($models);
    }

    /**
     * @param array $attributes
     * @param array $identifier
     * @param User|null $user
     * @return Taggable|mixed
     */
    public function update(array $attributes, array $identifier, ?User $user = null): mixed
    {
        $model = $this->storeOrUpdate($attributes, $identifier);
        $this->cacheService->flushModelCache($identifier, $user);

        return $model;
    }

    /**
     * @param array $identifier
     * @param User|null $user
     * @return mixed
     */
    public function destroy(array $identifier, ?User $user = null)
    {
        $model = ($this->modelClass)::firstWhere($identifier);
        $model->delete();
        $this->cacheService->flushModelCache($identifier, $user);

        return $model;
    }

    protected function storeOrUpdate(array $attributes,array $identifier = null)
    {

//        $tags = $attributes['tags'] ?? null;
//        unset($attributes['tags']);

        if ($identifier) {
           $model = ($this->modelClass)::updateOrCreate($identifier, $attributes);
        } else {
           $model = ($this->modelClass)::updateOrCreate($attributes);
        }

//        if ($model instanceof Taggable) {
//            $model->syncTags($tags);
//        }

        return $model;
    }

    public function getCacheService(): CacheService
    {
        return $this->cacheService;
    }
}