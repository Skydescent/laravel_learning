<?php

namespace App\Repositories;

use Illuminate\Http\Request;

class SimpleEloquentRepository extends EloquentRepository
{
    // TODO: Remove if not needed
    protected function prepareAttributes(Request $request = null) : array
    {
        return $request->all();
    }
}