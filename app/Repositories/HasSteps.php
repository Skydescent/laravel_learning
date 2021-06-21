<?php

namespace App\Repositories;

use App\Service\EloquentCacheService;
use App\Service\HasStepsServiceable;
use App\Service\StepsService;
use App\Step;
use App\Stepable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

trait HasSteps
{
    protected EloquentCacheService $stepsCacheService;

    protected HasStepsServiceable $stepsService;

    protected function initializeStepServices()
    {
        $this->stepsCacheService = EloquentCacheService::getInstance(Step::class);
        $this->stepsService = new StepsService();
    }

    public function addStep(FormRequest|Request $request, Stepable $model)
    {
        $this->initializeStepServices();
        $this->stepsService->addStep($request, $model);
        $this->forgetStepCache($model);
    }

    public function completeStep(Step $step, Stepable $model = null)
    {
        $this->initializeStepServices();
        $this->stepsService->completeStep($step);
        $this->forgetStepCache($model);
    }

    public function incompleteStep(Step $step, $model = null)
    {
        $this->initializeStepServices();
        $this->stepsService->incompleteStep($step);
        $this->forgetStepCache($model);
    }

    protected function forgetStepCache($morphedModel)
    {
        $this->stepsCacheService->forgetMorphedModelRelation($morphedModel, ['relation' => 'steps'], auth()->user());

    }
}