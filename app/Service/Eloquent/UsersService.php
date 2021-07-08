<?php

namespace App\Service\Eloquent;

use App\Repositories\Eloquent\UserRepository;
use App\Models\User;

class UsersService extends Service
{

    protected function setModelClass()
    {
        $this->modelClass = User::class;
    }

    protected function setRepository()
    {
        $this->repository = UserRepository::getInstance($this->modelClass);
    }
}