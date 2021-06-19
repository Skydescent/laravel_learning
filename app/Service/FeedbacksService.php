<?php

namespace App\Service;

use App\Feedback;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Http\Request;

class FeedbacksService implements RepositoryServiceable
{

    public function store(ValidatesWhenResolved|Request $request)
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'body' => 'required'
        ]);

        Feedback::create($attributes);
    }

    public function update(ValidatesWhenResolved|Request $request, $model)
    {
        // TODO: Implement update() method.
    }

    public function destroy($model)
    {
        // TODO: Implement destroy() method.
    }
}