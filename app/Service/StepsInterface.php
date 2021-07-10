<?php

namespace App\Service;

use App\Models\Stepable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

interface StepsInterface
{
    public function addStep(array $attributes, Stepable $model);

    public function updateStep(array $attributes, $identifier, $user, string $morphedModelName);

}