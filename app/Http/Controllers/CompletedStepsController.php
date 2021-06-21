<?php

namespace App\Http\Controllers;

use App\Repositories\StepableInterface;
use App\Step;

class CompletedStepsController extends Controller
{
    protected StepableInterface $modelRepositoryInterface;

    public function __construct(StepableInterface $modelRepositoryInterface)
    {
        $this->middleware('auth');
        $this->modelRepositoryInterface = $modelRepositoryInterface;
    }

    public function store(Step $step)
    {
        $this->modelRepositoryInterface->completeStep($step, $step->task);

        return back();
    }

    public function destroy(Step $step)
    {
        $this->modelRepositoryInterface->incompleteStep($step, $step->task);
        return back();
    }
}
