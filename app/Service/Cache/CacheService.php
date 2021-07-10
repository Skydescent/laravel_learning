<?php

namespace App\Service\Cache;

use App\Models\User;
use Cache;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;
use function config;

abstract class CacheService
{
    const CACHE_SERVICE_CONFIG_KEY = 'cache.cache_service';

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
        $allConfigs = config(self::CACHE_SERVICE_CONFIG_KEY);
        $this->configs = array_diff_key($allConfigs, [static::$configsMap => '']);

        if (array_key_exists($this->configKey,$allConfigs[static::$configsMap])) {
            $this->configs = array_merge($this->configs, $allConfigs[static::$configsMap][$this->configKey]);
        }
    }

    public static function getInstance(string $configKey)
    {
        static::setConfigsMap();

        if (!config('cache.cache_service.' . static::$configsMap . '.' . $configKey)) return null;

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
        $user = !$user || !$user->id ? null : $user;
        $key = $this->getKeyName($user, $postfixes);

        $data = Cache::tags($tags)->get($key);

        if (! is_null($data)) {
            return $data;
        }

        if ($data = $queryData()) {
            Cache::tags($tags)->put($key, $data, $this->configs['ttl']);
            return $data;
        }

        return null;
    }

    public function getTagName()
    {
        return  $this->configs['tag'] ??  $this->configKey;
    }

    abstract protected function getKeyName(Authenticatable|User $user = null, $postfixes = []): string;

    public function getCurrentCacheServiceConfigMap()
    {
        return config(self::CACHE_SERVICE_CONFIG_KEY . '.' . static::$configsMap);
    }

}