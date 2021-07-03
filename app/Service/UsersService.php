<?php

namespace App\Service;

use App\Repositories\UserEloquentRepository;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;

class UsersService extends EloquentService
{

    protected function setModelClass()
    {
        $this->modelClass = User::class;
    }

    protected function setRepository()
    {
        $this->repository = UserEloquentRepository::getInstance($this->modelClass);
    }
}