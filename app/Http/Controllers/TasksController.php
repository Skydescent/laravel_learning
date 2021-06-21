<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreAndUpdateRequest;
use App\Repositories\EloquentRepositoryInterface;
use App\Tag;
use App\Task;

class TasksController extends Controller
{
    protected EloquentRepositoryInterface $modelRepositoryInterface;

    public function __construct(EloquentRepositoryInterface $modelRepositoryInterface)
    {
        $this->middleware('auth');
        $this->middleware('can:update,task')->except(['index', 'store', 'create']);
        $this->modelRepositoryInterface = $modelRepositoryInterface;
    }

    public function index()
    {
        $tasks = $this->modelRepositoryInterface->publicIndex(auth()->user());
        return view('tasks.index', compact( 'tasks'));
    }

    public function show(Task $task)
    {
        $task = $this->modelRepositoryInterface->find($task, auth()->user());
        return view('tasks.show', compact('task'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(TaskStoreAndUpdateRequest $request)
    {
        $this->modelRepositoryInterface->store($request);
        return redirect('/tasks');
    }

    public function edit(Task $task)
    {
        $task = $this->modelRepositoryInterface->find($task, auth()->user());
        return view('tasks.edit', compact('task'));
    }

    public function update(TaskStoreAndUpdateRequest $request,Task $task)
    {
        $this->modelRepositoryInterface->update($request,$task, auth()->user());
        return redirect('/tasks');
    }

    public function destroy(Task $task)
    {
        $this->modelRepositoryInterface->destroy($task, auth()->user());
        return redirect('/tasks');
    }

}
