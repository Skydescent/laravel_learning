<?php

namespace App\Repositories;

use App\Models\User;

interface EloquentRepositoryInterface
{
    public function find(callable $getModel, array $identifier, ?User $user = null);

    public function index(callable $getIndex, string $modelKeyName, ?User $user = null, array $postfixes = []);

}