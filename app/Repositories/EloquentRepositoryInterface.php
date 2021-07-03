<?php


namespace App\Repositories;


use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

interface EloquentRepositoryInterface
{
    public function find(callable $getModel, array $identifier, ?User $user = null);

    public function index(callable $getIndex, string $modelKeyName, ?User $user = null, array $postfixes = []);

    //TODO: may be add store update, destroy ...

}