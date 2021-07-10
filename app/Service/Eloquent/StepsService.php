<?php

namespace App\Service\Eloquent;

use App\Notifications\TaskStepCompleted;
use App\Repositories\Eloquent\StepRepository;
use App\Service\StepsInterface;
use App\Models\Step;
use App\Models\Stepable;

class StepsService extends Service implements StepsInterface
{
    public function addStep(array $attributes, Stepable $model)
    {
        $this->repository->store($attributes, $model, cachedUser());
    }

    public function updateStep(array $attributes, $identifier, $user, string $morphedModelName)
    {
        $morphedModel = $this->find($identifier, $user)->$morphedModelName->model;
        $this->repository->update($attributes, $this->getModelIdentifier($identifier), $user, $morphedModel);
        $this->notifyOwnerTaskStepCompleted($user);
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