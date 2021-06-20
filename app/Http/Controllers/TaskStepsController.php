<?php

namespace App\Http\Controllers;

use App\Repositories\RepositoryStepableInterface;
use App\Task;

class TaskStepsController extends Controller
{
    protected RepositoryStepableInterface $modelInterface;

    public function __construct(RepositoryStepableInterface $modelInterface)
    {
        $this->modelInterface = $modelInterface;
    }

    public function store(Task $task)
    {
        $this->modelInterface->addStep(\request(), $task);

        return back();
    }
}
