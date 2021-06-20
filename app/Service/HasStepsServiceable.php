<?php

namespace App\Service;

use App\Step;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface HasStepsServiceable
{
    public function addStep(FormRequest|Request $request, \App\Stepable $model);

    public function completeStep(Step $step);

    public function incompleteStep(Step $step);
}