<?php

namespace App\Service;

use App\Feedback;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class FeedbacksService implements RepositoryServiceable
{

    public function store(FormRequest|Request $request)
    {
        $attributes = $request->validate([
            'email' => 'required|email',
            'body' => 'required'
        ]);

        Feedback::create($attributes);
    }

    public function update(FormRequest|Request $request, $model)
    {

    }

    public function destroy($model)
    {

    }
}