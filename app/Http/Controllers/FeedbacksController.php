<?php

namespace App\Http\Controllers;

use App\Feedback;
use Illuminate\Http\Request;

class FeedbacksController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::latest()->get();
        return view('feedbacks.index', compact( 'feedbacks'));
    }

    public function create()
    {
        return view('feedbacks.create');
    }

    public function store(Request $request)
    {
        request()->validate([
            'email' => 'required|email',
            'body' => 'required'
        ]);


        Feedback::create(request()->all());

        return redirect('/');

    }
}
