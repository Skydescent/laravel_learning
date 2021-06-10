<?php


namespace App\Repositories;


use App\Cache\CacheEloquentWrapper;
use App\Service\CacheService;
use App\Service\RepositoryServiceable;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Routing\UrlRoutable;

abstract  class EloquentRepository implements EloquentRepositoryInterface
{
    /**
     * @var RepositoryServiceable
     */
    protected RepositoryServiceable $modelService;

    /**
     * @var CacheService
     */
    protected CacheService $cacheService;

    /**
     * @var EloquentRepositoryInterface
     */
    private static EloquentRepositoryInterface $instance;

    /**
     * @var string
     */
    protected static string $model;

    /**
     *
     */
    protected function __construct()
    {
        $this->setCacheService();
        $this->setModelService();
    }

    /**
     *
     */
    abstract protected static function setModel();

    /**
     *
     */
    abstract protected function setCacheService();

    /**
     *
     */
    abstract protected function setModelService();

    /**
     *
     */
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
    public static function getInstance(): EloquentRepositoryInterface
    {
        static::setModel();
        if (!isset(self::$instance)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @param UrlRoutable $model
     * @param Authenticatable|User|null $user
     * @return mixed
     */
    public function find(UrlRoutable $model, Authenticatable|User|null $user = null): mixed
    {
        $identifier = $this->cacheService->getModelIdentifier($model);
        $data = (self::$model)::firstWhere($identifier);
        return  $this->cacheService->cacheItem($data, $identifier, $user);
    }

    /**
     * @param Authenticatable|User|null $user
     * @param array $postfixes
     * @return mixed
     */
    public function publicIndex(Authenticatable|User|null $user = null, array $postfixes = []): mixed
    {
        return $this->cacheService->cacheCollection(
            (self::$model)::all(),
            $user,
            $postfixes
        );
    }

    /**
     * @param $request
     */
    public function store($request)
    {
        $this->modelService->store($request);
        $this->cacheService->flushCollections();
    }

    /**
     * @param $request
     * @param $model
     * @param Authenticatable|User|null $user
     */
    public function update($request, $model, Authenticatable|User|null $user = null)
    {
        $this->modelService->update($request, $model);
        $this->cacheService->flushModelCache($model, $user);
    }

    /**
     * @param $model
     * @param Authenticatable|User|null $user
     */
    public function destroy($model, Authenticatable|User|null $user = null)
    {
        $this->modelService->destroy($model);
        $this->cacheService->flushModelCache($model, $user);
    }
}