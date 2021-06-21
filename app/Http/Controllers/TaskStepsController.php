<?php

namespace App\Http\Controllers;

use App\Repositories\StepableInterface;
use App\Task;

class TaskStepsController extends Controller
{
    protected StepableInterface $modelRepositoryInterface;

    public function __construct(StepableInterface $modelRepositoryInterface)
    {
        $this->modelRepositoryInterface = $modelRepositoryInterface;
    }

    public function store(Task $task)
    {
        $this->modelRepositoryInterface->addStep(\request(), $task);

        return back();
    }
}
