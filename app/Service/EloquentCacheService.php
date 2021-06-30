<?php


namespace App\Service;

use App\Cache\CacheEloquentWrapper;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Contracts\Pagination\Paginator as PaginatorInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorInterface;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Facades\Log;

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

    public function cacheModel(callable $getModel, array $identifier, ?User $user = null): ?CacheEloquentWrapper
    {
        $cache = $this->cache($getModel, $user, $identifier);

        return CacheEloquentWrapper::wrapModel($cache,$identifier, $this);
    }

    public function cacheIndex($getIndex, $user, $postfixies, $modelKeyName)
    {
        $cache = $this->cache($getIndex, $user, $postfixies, [$this->getTagName() . '_collection']);

        $indexInterfaces = class_implements($cache);
        $paginatorInterfaces = [PaginatorInterface::class, LengthAwarePaginatorInterface::class];
        $collectionInterfaces = [Enumerable::class];

        if(count(array_intersect($paginatorInterfaces, $indexInterfaces)) !== 0) {
            return CacheEloquentWrapper::wrapPaginator($cache,$this,$modelKeyName);
        }

        if (count(array_intersect($collectionInterfaces, $indexInterfaces)) !== 0) {
            return CacheEloquentWrapper::wrapCollection($cache,$this,$modelKeyName);
        }
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

    public function flushModelCache(array $identifier, User|null $user = null)
    {
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
        Log::info('EloquentCacheService@flushCollections: \Cache::tags([' . $this->getTagName() . '_collection])->flush()');
        \Cache::tags([$this->getTagName() . '_collection'])->flush();

    }

    public function getRelationsNames(): array
    {
        return isset($this->configs['relations']) ? array_keys($this->configs['relations']) : [];
    }

    public function getModelIdentifier(UrlRoutable|null $model =  null,string $identifier = null) : array
    {
        if (
            (!$model && !class_exists($this->configKey)) ||
            (!$model && !in_array(UrlRoutable::class,class_implements($this->configKey)))
        ) return [];

        if (!$model){
            return [(new $this->configKey())->getRouteKeyName() => $identifier];
        }

        $routeKeyName = $model->getRouteKeyName();
        $instanceKeyName = $model->$routeKeyName;
        return [$routeKeyName => $instanceKeyName];
    }

}