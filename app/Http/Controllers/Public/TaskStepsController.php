<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\StepsController;
use App\Service\Serviceable;
use App\Service\StepsInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TaskStepsController extends StepsController
{
    protected Serviceable $tasksService;

    /**
     * @param StepsInterface $stepsService
     * @param Serviceable $tasksService
     */
    public function __construct(StepsInterface $stepsService, Serviceable $tasksService)
    {
        parent::__construct($stepsService);
        $this->tasksService = $tasksService;
    }

    public function store(Request $request, $id): RedirectResponse
    {
        $task = $this
            ->tasksService
            ->find($id, cachedUser())
            ->model;

        $this->stepsService->addStep($this->prepareAttributes($request), $task);

        return back();
    }
}
