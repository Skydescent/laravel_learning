<?php

namespace App\Repositories;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

trait HasTagsCloud
{
    public function tagsCloud(Authenticatable|User|null $user, array $postfixes = [])
    {
        $postfixes['on_page'] =  $this->cacheService->getTagName();
        $filter = (new \App\Service\TagService)->getFilterCallback();
        $tagsCollection = \App\Tag::tagsCloud($filter);
        $tagsCacheService = \App\Service\CacheService::getInstance(\App\Tag::class);
        $additionalCurrentRepositoryCollectionTag = [$this->cacheService->getTagName() . '_collection'];

        return $tagsCacheService->cacheCollection($tagsCollection, $user, $postfixes, $additionalCurrentRepositoryCollectionTag);
    }
}