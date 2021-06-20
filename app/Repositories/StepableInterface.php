<?php

namespace App\Repositories;

use App\Stepable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface StepableInterface
{
    public function addStep(FormRequest|Request $request, \App\Stepable $model);

    public function completeStep(\App\Step $step, Stepable $model = null);

    public function incompleteStep(\App\Step $step, $model = null);
}