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

        if(!$cache) {
            return null;
        }

        return CacheEloquentWrapper::wrapModel($cache,$identifier, $this, $user);
    }

    public function cacheIndex(callable $getIndex, User $user, $postfixies, $modelKeyName, $tags = [])
    {
        $tags = count($tags)!== 0 ? [$this->getTagName() . '_collection', $tags] : [$this->getTagName() . '_collection'];
        $cache = $this->cache($getIndex, $user, $postfixies, $tags);

        $indexInterfaces = class_implements($cache);
        $paginatorInterfaces = [PaginatorInterface::class, LengthAwarePaginatorInterface::class];
        $collectionInterfaces = [Enumerable::class];

        if(count(array_intersect($paginatorInterfaces, $indexInterfaces)) !== 0) {
            return CacheEloquentWrapper::wrapPaginator($cache,$this,$modelKeyName, $user);
        }

        if (count(array_intersect($collectionInterfaces, $indexInterfaces)) !== 0) {
            return CacheEloquentWrapper::wrapCollection($cache,$this,$modelKeyName, $user);
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

    public function flushModelCache(array $identifier, ?User $user = null)
    {
        $this->forgetModel($identifier, $user);
        $this->forgetModelRelations($identifier, $user);
        $this->flushCollections();
    }

    public function forgetModel(array $identifier, ?User $user = null)
    {
        $keyName = $this->getKeyName($user, $identifier);
        $tag = $this->getTagName();

        \Cache::tags([$tag])->forget($keyName);
    }

    public function forgetModelRelations(array $identifier = null, ?User $user = null)
    {
        foreach ($this->getRelationsNames() as $relationName) {
            $this->forgetModelRelation(
                $identifier,
                ['relation' => $relationName],
                $user
            );
        }
    }

    public function forgetMorphedModelRelation(
        UrlRoutable $model,
        array       $relationName,
        ?User       $user = null
    )
    {
        $morphedCacheService = static::getInstance(get_class($model));
        $identifier = $morphedCacheService->getModelIdentifier($model);
        $morphedCacheService->forgetModelRelation($identifier, $relationName, $user);
    }

    public function forgetModelRelation(
        array $identifier,
        array $relationName,
        ?User $user = null,
        array $tags = []
    )
    {
        $postfixes = array_merge( $identifier, $relationName);
        $keyName = $this->getKeyName($user, $postfixes);

        $tags = count($tags) !== 0 ? $tags : [$this->getTagName()];

        Log::info('Cache::tags(' . implode(',', $tags) . ')->forget('. $keyName .')');
        \Cache::tags($tags)->forget($keyName);
    }

    public function flushCollections()
    {
        Log::info('Cache::tags(' . $this->getTagName() . '_collection)->flush()');
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