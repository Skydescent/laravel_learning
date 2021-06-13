<?php

namespace App\Repositories;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

interface RepositoryTaggableInterface
{
    public function tagsCloud(Authenticatable|User|null $user, array $postfixes = []);

    public function attachTags($tagsToAttach, $morphedModel, $syncIds, Authenticatable|User|null $user = null);
}