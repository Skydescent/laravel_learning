<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreAndUpdateRequest;
use App\Repositories\EloquentRepositoryInterface;
use App\Tag;
use App\Task;

class TasksController extends Controller
{
    protected EloquentRepositoryInterface $modelInterface;

    public function __construct(EloquentRepositoryInterface $modelInterface)
    {
        $this->middleware('auth');
        $this->middleware('can:update,task')->except(['index', 'store', 'create']);
        $this->modelInterface = $modelInterface;
    }

    public function index()
    {
        $tasks = $this->modelInterface->publicIndex(auth()->user());
        return view('tasks.index', compact( 'tasks'));
    }

    public function show(Task $task)
    {
        $task = $this->modelInterface->find($task, auth()->user());
        return view('tasks.show', compact('task'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(TaskStoreAndUpdateRequest $request)
    {
        $this->modelInterface->store($request);
        return redirect('/tasks');
    }

    public function edit(Task $task)
    {
        $task = $this->modelInterface->find($task, auth()->user());
        return view('tasks.edit', compact('task'));
    }

    public function update(TaskStoreAndUpdateRequest $request,Task $task)
    {
        $this->modelInterface->update($request,$task, auth()->user());
        return redirect('/tasks');
    }

    public function destroy(Task $task)
    {
        $this->modelInterface->destroy($task, auth()->user());
        return redirect('/tasks');
    }

}
