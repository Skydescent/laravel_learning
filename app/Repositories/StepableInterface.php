<?php

namespace App\Repositories;

use App\Step;
use App\Stepable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface StepableInterface
{
    public function addStep(FormRequest|Request $request, Stepable $model);

    public function completeStep(Step $step, Stepable $model = null);

    public function incompleteStep(Step $step, $model = null);
}