<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;

class FeedbacksController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::latest()->get();
        return view('admin.feedbacks.index', compact( 'feedbacks'));
    }

    public function create()
    {
        return view('feedbacks.create');
    }

    public function store()
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'body' => 'required'
        ]);

        Feedback::create($attributes);

        return redirect('/');

    }
}
