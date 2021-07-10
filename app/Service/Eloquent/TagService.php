<?php

namespace App\Service\Eloquent;

use App\Models\Tag;
use App\Models\Taggable;
use App\Models\User;
use App\Repositories\Eloquent\TagRepository;
use App\Service\Serviceable;
use App\Service\TagsInterface;
use Illuminate\Support\Facades\Log;

class TagService extends Service implements TagsInterface
{

    private array $dependsOnModels;

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

        return $this->repository->index(
            $getTagsCloud,
            null,
            cachedUser(),
            [],
            $this->dependsOnModels
        );
    }

    public function syncTags(string $requestTags, Taggable $model)
    {
        if (is_null($requestTags)) return;

        $syncIds = [];
        $tags = collect($this->cleanTagsString($requestTags));

        if ($model->tags->isNotEmpty()) {
            $itemTags = $model->tags->keyBy('name');
            $tags = $tags->keyBy(function ($item) { return $item; });
            $syncIds = $itemTags->intersectByKeys($tags)->pluck('id')->toArray();
            $tagsToAttach = $tags->diffKeys($itemTags);
        } else {
            $tagsToAttach = $tags;
        }

        $this->attachTags($tagsToAttach, $model, $syncIds);

    }

    private function cleanTagsString(string $tags) : array
    {
        return preg_split("/(^\s*)|(\s*,\s*)/", $tags, 0,PREG_SPLIT_NO_EMPTY);
    }


    protected function attachTags($tagsToAttach, $morphedModel, $syncIds)
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
        $this->modelClass = Tag::class;
    }

    /**
     * @return void
     */
    protected function setRepository() : void
    {
        $this->repository = TagRepository::getInstance($this->modelClass);
    }

    public function storeWithTagsSync(Serviceable $service, array $attributes)
    {
        $this->storeOrUpdateWithTagsSync($service,'store', $attributes);
    }

    public function updateWithTagsSync(Serviceable $service, array $attributes,string $identifier, ?User $user = null)
    {
        $this->storeOrUpdateWithTagsSync($service, 'update', $attributes, $identifier, $user);
    }

    protected function storeOrUpdateWithTagsSync(Serviceable $service,string $method, array $attributes,...$args)
    {
        $tags = $attributes['tags'] ?? null;
        unset($attributes['tags']);

        $service->$method($attributes, ...$args);

        $model = $service->getCurrentModel();

        if ($model instanceof Taggable && !is_null($tags)) {
            $this->syncTags($tags,$model);
        }
    }
}