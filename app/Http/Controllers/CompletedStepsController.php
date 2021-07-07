<?php

namespace App\Http\Controllers;

use App\Service\StepsInterface;
use Illuminate\Http\Request;

class CompletedStepsController extends Controller
{
    /**
     * @var StepsInterface
     */
    protected StepsInterface $stepsService;

    /**
     * @param StepsInterface $stepsService
     */
    public function __construct(StepsInterface $stepsService)
    {
        $this->stepsService = $stepsService;
    }


    public function store(Request $request, $id)
    {
        $this->stepsService->updateStep($request, $id, cachedUser(), 'task');

        return back();
    }

    public function destroy(Request $request, $id)
    {
        return $this->store($request, $id);
    }
}
