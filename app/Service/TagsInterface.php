<?php

namespace App\Service;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

interface TagsInterface
{
    public function tagsCloud();

    //public function attachTags($tagsToAttach, $morphedModel, $syncIds, Authenticatable|User|null $user = null);
}