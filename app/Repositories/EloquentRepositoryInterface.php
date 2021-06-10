<?php


namespace App\Repositories;


use App\User;

interface EloquentRepositoryInterface
{
    public function find(\Illuminate\Contracts\Routing\UrlRoutable $model, User|null $user = null);

    public function publicIndex(User|null $user = null, array $postfixes = []);
}