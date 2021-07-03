<?php


namespace App\Service;

use App\Repositories\TagEloquentRepository;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class TagService extends EloquentService implements TagsInterface
{

    private array $dependsOnModels = [];

    public function __construct()
    {
        parent::__construct();
        $this->dependsOnModels = config('tags.public_visible_related_models');
    }

    public function tagsCloud()
    {
        $getTagsCloud = function () {
            $filter = $this->getFilterCallback();
            return ($this->modelClass)::tagsCloud($filter);
        };

        return $this->repository->index($getTagsCloud, null, cachedUser(), $this->dependsOnModels);
    }

    public function attachTags($tagsToAttach, $morphedModel, $syncIds, ?User $user = null)
    {
        $tagsToCreate = [];
        foreach ($tagsToAttach as $tag) {
            $identifier = ['name' => $tag];
            $tag = $this->find($identifier);
            if (!$tag) {
                $tagsToCreate[] = $identifier;
            } else {
                $syncIds[] = $tag->id;
            }
        }
        $syncIds = array_merge(
            $syncIds,
            $this
                ->repository
                ->createMany($tagsToCreate)
                ->pluck('id')->all()
        );

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

    /**
     * @return void
     */
    protected function setModelClass() : void
    {
        $this->modelClass = \App\Tag::class;
    }

    /**
     * @return void
     */
    protected function setRepository() : void
    {
        $this->repository = TagEloquentRepository::getInstance($this->modelClass);
    }
}