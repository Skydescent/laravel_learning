<?php

namespace App;

interface Taggable
{
    /**
     * Sync current model tags.
     *
     * @param string $requestTags
     * @return void
     */
    public function syncTags(string $requestTags);

}