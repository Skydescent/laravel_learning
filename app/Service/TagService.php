<?php


namespace App\Service;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class TagService implements TagsInterface
{

    private array $dependsOnModels = [];

    private static string $modelClass = \App\Tag::class;

    private EloquentCacheService $cacheService;

    public function __construct()
    {
        $this->dependsOnModels = config('tags.public_visible_related_models');
        $this->cacheService = \App\Service\EloquentCacheService::getInstance(static::$modelClass);
    }

    public function tagsCloud()
    {
        $getTagsCloud = function () {
            $filter = $this->getFilterCallback();
            return (static::$modelClass)::tagsCloud($filter);
        };

        $tags = $this->getDependsOnModelsTags();
        $tags[] = $this->cacheService->getTagName() . '_collection';

        return $this->cacheService->cache($getTagsCloud, auth()->user(), [], $tags);
    }

    public function attachTags($tagsToAttach, $morphedModel, $syncIds, Authenticatable|User|null $user = null)
    {
        $this->cacheService->flushCollections();

        foreach ($tagsToAttach as $tag) {
            $identifier = ['name' => $tag];
            $tag = \App\Tag::firstWhere($identifier);

            if (!$tag) {
                $tag = \App\Tag::create($identifier);
            }
            $syncIds[] = $tag->id;
        }

        $morphedModel->tags()->sync($syncIds);
    }

    protected function getFilterCallback() : callable
    {
        return function ($query)
        {
            return $this->getModelQueryFilter($this->dependsOnModels,$query);
        };
    }

    protected function getModelQueryFilter($models, $query, $queryFilterName = 'queryFilter')
    {
        foreach ($models as $model => $options){
            if(method_exists( new $model(), $queryFilterName)) {
                $query = call_user_func_array([new $model(), $queryFilterName], [$query]);
            } else {
                $query = $query->orHas($options['relation']);
            }
        }
        return $query;
    }

    protected function getDependsOnModelsTags() : array
    {
        $tags = [];
        $map = $this->cacheService->getCurrentCacheServiceConfigMap();
        foreach ($map as $model => $modelOptions) {
            if(in_array($model, array_keys($this->dependsOnModels))) {
                $tags[] = $modelOptions['tag'] . '_collection';
            }
        }
        return $tags;
    }
}