<?php

namespace App\Service;

use App\Models\Stepable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface StepsInterface
{
    public function addStep(FormRequest|Request $request, Stepable $model);

    public function updateStep(Request $request, $identifier, $user, string $morphedModelName);

}