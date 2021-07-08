<?php

namespace App\Service\Eloquent;

use App\Notifications\TaskStepCompleted;
use App\Repositories\Eloquent\StepRepository;
use App\Service\StepsInterface;
use App\Models\Step;
use App\Models\Stepable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StepsService extends Service implements StepsInterface
{
    public array $afterEventMethods = [
        'notifyOwnerTaskStepCompleted' => [
            'update' =>[],
        ]
    ];

    public function addStep(FormRequest|Request $request, Stepable $model)
    {
        $this->repository->store($request, $model, cachedUser());
    }

    public function updateStep(Request $request, $identifier, $user, string $morphedModelName)
    {
        $morphedModel = $this->find($identifier, $user)->$morphedModelName->model;
        $this->repository->update($request, $this->getModelIdentifier($identifier), $user, $morphedModel);
        $this->callMethodsAfterEvent('update', $user);
    }

    protected function setModelClass()
    {
        $this->modelClass = Step::class;
    }

    protected function setRepository()
    {
        $this->repository = StepRepository::getInstance($this->modelClass);
    }

    public function notifyOwnerTaskStepCompleted($owner)
    {
        $owner->notify(new TaskStepCompleted());
    }
}