<?php


namespace App;


trait SynchronizeTags
{
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

        $taggableRepository = (new \App\Service\RepositoryService())->getTaggableRepository();
        $taggableRepository->attachTags($tagsToAttach, $this, $syncIds, auth()->user());

    }

    private function cleanTagsString(string $tags) : array
    {
        return preg_split("/(^\s*)|(\s*,\s*)/", $tags, 0,PREG_SPLIT_NO_EMPTY);
    }
}
