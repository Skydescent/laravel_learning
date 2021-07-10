<?php

namespace App\Service;

use App\Models\Taggable;
use App\Models\User;

interface TagsInterface
{
    public function tagsCloud();

    public function syncTags(string $requestTags, Taggable $model);

    public function storeWithTagsSync(Serviceable $service, array $attributes);

    public function updateWithTagsSync(Serviceable $service, array $attributes,string $identifier, ?User $user = null);
}