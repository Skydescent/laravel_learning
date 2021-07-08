<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskStoreAndUpdateRequest;
use App\Service\Serviceable;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    /**
     * @var Serviceable
     */
    protected Serviceable $tasksService;

    /**
     * @param Serviceable $tasksService
     */
    public function __construct(Serviceable $tasksService)
    {
        $this->middleware('auth');
        $this
            ->middleware('model.from.cache:' . get_class($tasksService) . ',task')
            ->only(['show', 'edit', 'update', 'destroy']);
        $this->tasksService = $tasksService;
    }

    public function index()
    {
        $tasks = $this->tasksService->index(cachedUser());
        return view('tasks.index', compact( 'tasks'));
    }

    public function show(Request $request)
    {
        $task = $request->attributes->get('task');
        return view('tasks.show', compact('task'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(TaskStoreAndUpdateRequest $request)
    {
        $this->tasksService->store($request);
        return redirect('/tasks');
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Request $request)
    {
        $task = $request->attributes->get('task');
        $this->authorize('update', $task->model);

        return view('tasks.edit', compact('task'));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(TaskStoreAndUpdateRequest $request, $id)
    {

        $task = $request->attributes->get('task');
        $this->authorize('update', $task->model);

        $this->tasksService->update($request, $id, cachedUser());;
        return redirect('/tasks');
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Request $request)
    {
        $task = $request->attributes->get('task');
        $this->authorize('update', $task->model);

        $this->tasksService->destroy($task->id, cachedUser());

        return redirect('/tasks');
    }

}
