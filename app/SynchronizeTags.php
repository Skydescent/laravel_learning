<?php


namespace App;


trait SynchronizeTags
{
    public function syncTags($requestTags)
    {
        $syncIds = [];
        $tags = collect(explode(',', $requestTags));

        if ($this->tags->isNotEmpty()) {
            $taskTags = $this->tags->keyBy('name');
            $tags = $tags->keyBy(function ($item) { return $item; });
            $syncIds = $taskTags->intersectByKeys($tags)->pluck('id')->toArray();
            $tagsToAttach = $tags->diffKeys($taskTags);
        } else {
            $tagsToAttach = $tags;
        }

        foreach ($tagsToAttach as $tag) {
            $tag = Tag::firstOrCreate(['name' => $tag]);
            $syncIds[] = $tag->id;
        }

        $this->tags()->sync($syncIds);

    }
}
