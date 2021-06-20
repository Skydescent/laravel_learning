<?php

namespace App\Http\Controllers;

use App\Notifications\TaskStepCompleted;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\StepableInterface;
use App\Step;
use Illuminate\Http\Request;

class CompletedStepsController extends Controller
{
    protected StepableInterface $modelInterface;

    public function __construct(StepableInterface $modelInterface)
    {
        $this->middleware('auth');
        $this->modelInterface = $modelInterface;
    }

    public function store(Step $step)
    {
        $this->modelInterface->completeStep($step, $step->task);

        return back();
    }

    public function destroy(Step $step)
    {
        $this->modelInterface->incompleteStep($step, $step->task);
        return back();
    }
}
