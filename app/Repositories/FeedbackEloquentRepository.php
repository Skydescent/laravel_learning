<?php

namespace App\Repositories;

use App\Feedback;
use App\Service\EloquentCacheService;
use App\Service\FeedbacksService;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class FeedbackEloquentRepository extends EloquentRepository //implements TaggableInterface
{

    /**
     * @return mixed
     */
    protected function prepareAttributes($request = null): mixed
    {
        return $request->validate([
            'email' => 'required|email',
            'body' => 'required'
        ]);
    }
}