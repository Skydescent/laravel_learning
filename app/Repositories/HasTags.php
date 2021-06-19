<?php

namespace App\Repositories;

use App\Service\CacheService;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

trait HasTags
{
    //TODO: В контроллерах, где тэги просто отображаются списком, нужно их отделить
    // и не добавлять трейт ко всем контроллерам только для отображения облака тэгов, подумать как сделать это отдельно
    // возможно также как и для статистики??

    protected function getTagsCacheService(): CacheService
    {
        return \App\Service\CacheService::getInstance(\App\Tag::class);
    }

    public function tagsCloud(Authenticatable|User|null $user, array $postfixes = [])
    {
        $postfixes['on_page'] =  $this->cacheService->getTagName();
        $filter = (new \App\Service\TagService)->getFilterCallback();
        $tagsCollection = \App\Tag::tagsCloud($filter);
        $tagsCacheService = $this->getTagsCacheService();
        $additionalCurrentRepositoryCollectionTag = [$this->cacheService->getTagName() . '_collection'];

        return $tagsCacheService->cacheCollection($tagsCollection, $user, $postfixes, $additionalCurrentRepositoryCollectionTag);
    }

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