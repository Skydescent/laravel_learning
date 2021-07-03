<?php


namespace App\Repositories;


use App\Service\EloquentCacheService;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

abstract  class EloquentRepository implements EloquentRepositoryInterface
{
    protected string $modelClass;

    /**
     * @var EloquentCacheService
     */
    protected EloquentCacheService $cacheService;

    /**
     * @var array
     */
    protected static array $instances = [];
    
    protected function __construct($modelClass)
    {
        $this->modelClass = $modelClass;
    }

    abstract protected function prepareAttributes();

    protected function setCacheService($cacheService)
    {
        $this->cacheService = $cacheService;
    }
    
    protected function __clone()
    {

    }

    /**
     * @throws \Exception
     */
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize a singleton.");
    }


    /**
     * @return EloquentRepositoryInterface
     */
    public static function getInstance(string $modelClass): EloquentRepositoryInterface
    {
        if (!isset(static::$instances[$modelClass])) {
            static::$instances[$modelClass] = new static($modelClass);
        }
        static::$instances[$modelClass]->setCacheService(EloquentCacheService::getInstance($modelClass));
        return static::$instances[$modelClass];
    }

    public function index(callable $getIndex, string|null $modelKeyName = null, ?User $user = null, array $postfixes = [])
    {
        return $this->cacheService->cacheIndex($getIndex,$user,$postfixes, $modelKeyName);
    }

    /**
     * @param string|array $identifier
     * @param Authenticatable|User|null $user
     * @return mixed
     */
    public function find(callable $getModel, array $identifier, ?User $user = null): mixed
    {
        return  $this->cacheService->cacheModel($getModel, $identifier, $user);
    }

    /**
     * @param $request
     */
    public function store($request)
    {
        $model = $this->storeOrUpdate($request);
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
     * @param $request
     * @param array $identifier
     * @param Authenticatable|User|null $user
     */
    public function update(FormRequest|Request $request, array $identifier, ?User $user = null)
    {
        $model = $this->storeOrUpdate($request, $identifier);
        $this->cacheService->flushModelCache($identifier, $user);

        return $model;
    }

    /**
     * @param $model
     * @param Authenticatable|User|null $user
     */
    public function destroy(array $identifier, ?User $user = null)
    {
        $model = ($this->modelClass)::firstWhere($identifier);
        $model->delete();
        $this->cacheService->flushModelCache($identifier, $user);

        return $model;
    }

    protected function storeOrUpdate(FormRequest|Request $request,array $identifier = null)
    {
        $attributes = $this->prepareAttributes($request);

        $tags = $attributes['tags'] ?? null;
        unset($attributes['tags']);

        if ($identifier) {
           $model = ($this->modelClass)::updateOrCreate($identifier, $attributes);
        } else {
           $model = ($this->modelClass)::updateOrCreate($attributes);
        }

        if ($model instanceof \App\Taggable) {
            $model->syncTags($tags);
        }

        return $model;
    }
}