<?php

namespace App\Service;

use App\Repositories\TagEloquentRepository;
use App\Repositories\TaggableInterface;

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

    public function getTaggableRepository(string|null $action = null): TaggableInterface
    {
        static::getConfigs();
        $action = $action ?? $this->getAction();

        $controller = $this->getControllerFromAction($action);

        $map = static::$configs[TaggableInterface::class];
        foreach ($map as $item) {
            if(in_array($controller,$item['controllers'])) {
                $repository = $item['repository_closure'];
            }
        }

        if (
            isset($repository) &&
            in_array(
                TaggableInterface::class,
                class_implements($repository())
            )
        ) {
            return $repository();
        };

        return  TagEloquentRepository::getInstance();
    }
}