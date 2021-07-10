<?php


namespace App\Service\Eloquent;

use App\Cache\CacheEloquentWrapper;
use App\Service\Cache\CacheService as BaseCacheService;
use App\Models\User;
use Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Facades\Log;

class CacheService extends BaseCacheService
{
    /**
     */
    protected static function setConfigsMap()
    {
        static::$configsMap = 'map';
    }

    protected function initialize()
    {
        parent::initialize();
        unset($this->configs['simple_services']);
    }

    protected function __clone()
    {

    }

    public function cacheModel(
        callable $getModel,
        array $identifier,
        ?User $user = null
    ): ?CacheEloquentWrapper
    {
        $cache = $this->cache($getModel, $user, $identifier);

        if(!$cache) {
            return null;
        }

        return CacheEloquentWrapper::wrapModel($cache,$identifier, $this, $user);
    }

    public function cacheIndex(
        callable $getIndex,
        User $user,
        $postfixies,
        $modelKeyName, $tags = []
    )
    {
        $tags = count($tags)!== 0 ?
            [$this->getTagName() . '_collection', $tags] :
            [$this->getTagName() . '_collection'];
        $cache = $this->cache($getIndex, $user, $postfixies, $tags);

        return CacheEloquentWrapper::getWrapper($cache, [$modelKeyName => ''], $this, $user);
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
        $this->forgetModelRelations($identifier);
        $this->flushCollections();
    }

    public function forgetModel(array $identifier, ?User $user = null)
    {
        $keyName = $this->getKeyName($user, $identifier);
        $tag = $this->getTagName();

        Cache::tags([$tag])->forget($keyName);
    }

    public function forgetModelRelations(array $identifier)
    {
        $tag = $this->getRelationTag($identifier);
        Cache::tags($tag)->flush();
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
    )
    {
        $postfixes = array_merge( $identifier, $relationName);
        $keyName = $this->getKeyName($user, $postfixes);
        $tag = $this->getRelationTag($identifier);
        Cache::tags($tag)->forget($keyName);
    }

    public function flushCollections()
    {
        Cache::tags([$this->getTagName() . '_collection'])->flush();
    }

    public function getModelIdentifier(
        UrlRoutable|null $model =  null,
        string $identifier = null
    ) : array
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

    public function getRelationTag(array $identifier) : array
    {
        return [$this->getTagName() . '_' . implode($identifier) . '_relations'];
    }

}