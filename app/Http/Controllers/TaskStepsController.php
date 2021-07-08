<?php

namespace App\Http\Controllers;

use App\Service\Serviceable;
use App\Service\StepsInterface;
use Illuminate\Http\Request;

class TaskStepsController extends Controller
{
    /**
     * @var StepsInterface
     */
    protected StepsInterface $stepsService;

    protected Serviceable $tasksService;

    /**
     * @param StepsInterface $stepsService
     * @param Serviceable $tasksService
     */
    public function __construct(StepsInterface $stepsService, Serviceable $tasksService)
    {
        $this->stepsService = $stepsService;
        $this->tasksService = $tasksService;
    }

    public function store(Request $request, $id)
    {
        $task = $this
            ->tasksService
            ->find($id, cachedUser())
            ->model;

        $this->stepsService->addStep($request, $task);

        return back();
    }
}
