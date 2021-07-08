<?php


namespace App\Models;


use App\Service\Eloquent\TagService;
use App\Service\TagsInterface;

trait SynchronizeTags
{

    protected function getTagService() : TagsInterface
    {
        return new TagService();
    }

    public function syncTags($requestTags)
    {
        if (is_null($requestTags)) return;
        $syncIds = [];
        $tags = collect($this->cleanTagsString($requestTags));

        if ($this->tags->isNotEmpty()) {
            $itemTags = $this->tags->keyBy('name');
            $tags = $tags->keyBy(function ($item) { return $item; });
            $syncIds = $itemTags->intersectByKeys($tags)->pluck('id')->toArray();
            $tagsToAttach = $tags->diffKeys($itemTags);
        } else {
            $tagsToAttach = $tags;
        }

        $this->getTagService()->attachTags($tagsToAttach, $this, $syncIds, cachedUser());

    }

    private function cleanTagsString(string $tags) : array
    {
        return preg_split("/(^\s*)|(\s*,\s*)/", $tags, 0,PREG_SPLIT_NO_EMPTY);
    }
}
