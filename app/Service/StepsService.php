<?php

namespace App\Service;

use App\Notifications\TaskStepCompleted;
use App\Step;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class StepsService extends EloquentService implements StepsInterface
{
    public array $afterEventMethods = [
        'notifyOwnerTaskStepCompleted' => [
            'update' =>['добавлена статья', 'posts.show'],
        ]
    ];

    public function addStep(FormRequest|Request $request, \App\Stepable $model)
    {
        //Сохранить модель
        $model->addStep( $request->validate([
            'description' => 'required|min:5'
        ]));
    }

    public function updateStep(Request $request, $identifier, $user, string $morphedModelName)
    {
        $morphedModel = $this->find($identifier, $user)->$morphedModelName->model;
        $this->repository->update($request, $this->getModelIdentifier($identifier), $user, $morphedModel);
    }

    protected function setModelClass()
    {
        $this->modelClass = \App\Step::class;
    }

    protected function setRepository()
    {
        $this->repository = \App\Repositories\StepEloquentRepository::getInstance($this->modelClass);
    }

    public function notifyOwnerTaskStepCompleted()
    {
        $this->currentModel->task->owner->notify(new TaskStepCompleted());
    }
}