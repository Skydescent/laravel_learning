<?php

namespace App\Repositories\Eloquent;

use Illuminate\Http\Request;

class SimpleRepository extends Repository
{
    protected function prepareAttributes(Request $request = null) : array
    {
        return $request->all();
    }
}