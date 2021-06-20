<?php

namespace App\Service;

use App\Notifications\TaskStepCompleted;
use App\Step;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StepsService implements HasStepsServiceable
{

    public function addStep(FormRequest|Request $request, \App\Stepable $model)
    {
        $model->addStep( $request->validate([
            'description' => 'required|min:5'
        ]));
    }

    public function completeStep(Step $step)
    {
        $step->complete();
        $step->task->owner->notify(new TaskStepCompleted());
    }

    public function incompleteStep(Step $step)
    {
        $step->incomplete();
    }
}