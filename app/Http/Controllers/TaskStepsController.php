<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskStepsController extends Controller
{
    public function update(Step $step)
    {
        dd(\request()->all());
    }
}
