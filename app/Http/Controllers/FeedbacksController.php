<?php

namespace App\Http\Controllers;

use App\Feedback;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Http\Request;

class FeedbacksController extends Controller
{
    protected EloquentRepositoryInterface $modelInterface;

    public function __construct(EloquentRepositoryInterface $modelInterface)
    {
        $this->modelInterface = $modelInterface;
    }

    public function index()
    {
        $feedbacks = $this->modelInterface->adminIndex(auth()->user());
        return view('admin.feedbacks.index', compact( 'feedbacks'));
    }

    public function create()
    {
        return view('feedbacks.create');
    }

    public function store(Request $request)
    {
        $this->modelInterface->store($request);

        return redirect('/');
    }
}
