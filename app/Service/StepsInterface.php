<?php

namespace App\Service;

use App\Step;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface StepsInterface
{
    public function addStep(FormRequest|Request $request, \App\Stepable $model);

    public function updateStep(Request $request, $identifier, $user, string $morphedModelName);

}