<?php

namespace App\Repositories\Eloquent;

use App\Models\User;

class TagRepository extends SimpleRepository
{

    public function index(callable $getIndex, string|null $modelKeyName = null, ?User $user = null, array $postfixes = [],  array $dependsOnModels = null)
    {
        $tags = $dependsOnModels ? $this->getDependsOnModelsTags($dependsOnModels) : [];
        $tags[] = $this->cacheService->getTagName() . '_collection';

        return $this->cacheService->cache($getIndex, $user, [], $tags);
    }

    protected function getDependsOnModelsTags($dependsOnModels) : array
    {
        $tags = [];
        $map = $this->cacheService->getCurrentCacheServiceConfigMap();
        foreach ($map as $model => $modelOptions) {
            if(in_array($model, array_keys($dependsOnModels))) {
                $tags[] = $modelOptions['tag'] . '_collection';
            }
        }
        return $tags;
    }
}