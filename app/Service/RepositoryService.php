<?php

namespace App\Service;

use App\Repositories\RepositoryTaggableInterface;

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

    protected function getAction ()
    {
        return (
        \app('request')
            ->route()
            ->getAction()
        )['controller'];
    }

    public function getTaggableRepository(string|null $action = null): RepositoryTaggableInterface
    {
        static::getConfigs();
        $action = $action ?? $this->getAction();

        $controller = $this->getControllerFromAction($action);
        $map = static::$configs['controller_repository_map'];
        foreach ($map as $item) {
            if(in_array($controller,$item['controllers'])) {
                return $item['repository_closure']();
            }
        }
        return  \App\Repositories\TagEloquentRepository::getInstance();
    }
}