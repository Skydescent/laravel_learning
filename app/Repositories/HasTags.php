<?php

namespace App\Repositories;

use App\Service\EloquentCacheService;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

trait HasTags
{

    public function attachTags($tagsToAttach, $morphedModel, $syncIds, Authenticatable|User|null $user = null)
    {
        $tagCacheService = $this->getTagsCacheService();
        $tagCacheService->flushCollections();

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
}