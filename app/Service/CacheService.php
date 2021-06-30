<?php

namespace App\Service;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;

abstract class CacheService
{
    protected static array $instances = [];

    protected static string $configsMap;

    protected string $configKey;

    public array $configs;

    protected function __construct(string $configKey)
    {
        $this->configKey = $configKey;
        $this->initialize();
    }

    protected function __clone()
    {

    }

    abstract protected static function setConfigsMap();

    protected function initialize()
    {
        //TODO: Remove simple_services config from EloquentCacheServices
        $allConfigs = config('cache.cache_service');
        $this->configs = array_diff_key($allConfigs, [static::$configsMap => '']);

        if (array_key_exists($this->configKey,$allConfigs[static::$configsMap])) {
            $this->configs = array_merge($this->configs, $allConfigs[static::$configsMap][$this->configKey]);
        }
    }

    public static function getInstance(string $configKey)
    {
        static::setConfigsMap();

        if (!\config('cache.cache_service.' . static::$configsMap . '.' . $configKey)) return null;

        if (!isset(self::$instances[$configKey])) {
            self::$instances[$configKey] = new static($configKey);
        }

        return self::$instances[$configKey];
    }

    public function cache(
        callable             $queryData ,
        Authenticatable|User $user = null,
        array                $postfixes = [],
        array|null           $tags = null
    )
    {
        $tags = $tags ??  [$this->getTagName()];
        $key = $this->getKeyName($user, $postfixes);

        //Log::info('CacheService@cache: \Cache::tags(' . implode(',',$tags) . ')->remember(' . $key . ')');
        return \Cache::tags($tags)
            ->remember(
                $key,
                $this->configs['ttl'],
                $queryData
            );
    }

    public function getTagName()
    {
        return  $this->configs['tag'] ??  $this->configKey;
    }

    abstract protected function getKeyName(Authenticatable|User $user = null, $postfixes = []): string;

}