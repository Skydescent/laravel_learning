<?php

namespace App\Repositories\Eloquent;

class UserRepository extends Repository
{

    protected function prepareAttributes($request = null): array
    {
        return $request->toArray();
    }
}