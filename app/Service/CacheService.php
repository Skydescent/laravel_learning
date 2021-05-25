<?php


namespace App\Service;


use App\User;

class CacheService
{
    protected string $modelClass;

    public array $configs;

    public function __construct(string $modelClass)
    {
        $this->modelClass = $modelClass;
        $this->initialize();
    }

    protected function initialize()
    {
        $allConfigs = config('cache.cache_service');
        $this->configs = array_diff_key($allConfigs, ['map' => '']);

        if (array_key_exists($this->modelClass,$allConfigs['map'])) {
            $this->configs = array_merge($this->configs, $allConfigs['map'][$this->modelClass]);
        }
    }

    public function cache(callable $queryData , User $user = null, $postfixes = [])
    {
        $tag = $this->getTagName();
        $key = $this->getKeyName($user, $postfixes);

        return \Cache::tags([$tag])
            ->remember(
                $key,
                $this->configs['ttl'],
                $queryData
            );
    }

    public function getTagName()
    {
        return  $this->configs['tag'] ??  $this->modelClass;
    }


    public function getKeyName(User|null $user, $postfixes)
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
}