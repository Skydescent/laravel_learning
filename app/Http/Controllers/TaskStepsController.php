<?php

namespace App\Http\Controllers;

use App\Repositories\StepableInterface;
use App\Task;

class TaskStepsController extends Controller
{
    protected StepableInterface $modelInterface;

    public function __construct(StepableInterface $modelInterface)
    {
        $this->modelInterface = $modelInterface;
    }

    public function store(Task $task)
    {
        $this->modelInterface->addStep(\request(), $task);

        return back();
    }
}
