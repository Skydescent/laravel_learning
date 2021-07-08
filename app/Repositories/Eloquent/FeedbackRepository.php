<?php

namespace App\Repositories\Eloquent;

class FeedbackRepository extends Repository
{

    /**
     * @return mixed
     */
    protected function prepareAttributes($request = null) : array
    {
        return $request->validate([
            'email' => 'required|email',
            'body' => 'required'
        ]);
    }
}