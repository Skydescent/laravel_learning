<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\StepsController;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CompletedStepsController extends StepsController
{

    public function store(Request $request, $id): RedirectResponse
    {
        $this->stepsService->updateStep(
            $this->prepareAttributes($request),
            $id,
            cachedUser(),
            'task'
        );

        return back();
    }

    public function destroy(Request $request, $id): RedirectResponse
    {
        return $this->store($request, $id);
    }
}
