<?php


namespace App\Repositories;


use App\User;

interface EloquentRepositoryInterface
{
    public function find(array $identifier, User|null $user = null);

    public function publicAll(User|null $user = null, array $postfixes = []);
}