<?php

namespace App\Http\Controllers;

use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Http\Request;

class FeedbacksController extends Controller
{
    protected EloquentRepositoryInterface $modelRepositoryInterface;

    public function __construct(EloquentRepositoryInterface $modelRepositoryInterface)
    {
        $this->modelRepositoryInterface = $modelRepositoryInterface;
    }

    public function index()
    {
        $feedbacks = $this->modelRepositoryInterface->adminIndex(auth()->user());
        return view('admin.feedbacks.index', compact( 'feedbacks'));
    }

    public function create()
    {
        return view('feedbacks.create');
    }

    public function store(Request $request)
    {
        $this->modelRepositoryInterface->store($request);

        return redirect('/');
    }
}
