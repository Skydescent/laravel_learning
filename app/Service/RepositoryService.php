<?php

namespace App\Service;

use App\Repositories\EloquentRepositoryInterface;

class RepositoryService
{
    protected static array $configs;

    protected static function getConfigs()
    {
        static::$configs = config('cache.cache_repositories');
    }

    protected function getControllerFromAction(string $action): string
    {
        return explode('@',$action)[0];
    }

    public function getRepository(string $action): EloquentRepositoryInterface
    {
        static::getConfigs();
        $controller = $this->getControllerFromAction($action);
        $map = static::$configs['controller_repository_map'];
        foreach ($map as $item) {
            if(in_array($controller,$item['controllers'])) {
                return $item['repository_closure']();
            }
        }

    }
}