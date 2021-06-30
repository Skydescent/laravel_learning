<?php

namespace App\Service;

use App\Repositories\EloquentRepositoryInterface;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class EloquentService implements RepositoryServiceable
{
    public array $flashMessages = [];

    public array $afterEventMethods = [];

    protected Model $currentModel;

    protected string $modelClass;

    protected EloquentRepositoryInterface $repository;

    protected abstract function setModelClass();

    protected abstract function setRepository();

    public function __construct()
    {
        $this->setModelClass();
        $this->setRepository();
    }


    public function find(string $identifier, Authenticatable|null $user = null)
    {
        Log::info('EloquentService@find: ' . $identifier . ' user: ' . $user);
        $identifier = $this->getModelIdentifier($identifier);
        $getModel = function () use ($identifier) {
            return ($this->modelClass)::firstWhere($identifier);
        };

        return $this->repository->find($getModel, $identifier, $user);
    }

    public function store(FormRequest|Request $request)
    {
        $this->currentModel = $this->repository->store($request);
        $this->callMethodsAfterEvent('store');
    }

    public function  update(FormRequest|Request $request,string $identifier, Authenticatable|User|null $user = null)
    {
        $this->currentModel = $this->repository->update($request, $this->getModelIdentifier($identifier), $user);
        $this->callMethodsAfterEvent('update');
    }

    public function destroy(string $identifier, Authenticatable|User|null $user = null)
    {
        $this->currentModel = $this->repository->destroy($this->getModelIdentifier($identifier), $user);
        $this->callMethodsAfterEvent('destroy');
    }


    protected function flashEventMessage(string $eventName)
    {
        if (
            count($this->flashMessages) !== 0 &&
            array_key_exists($eventName, $this->flashMessages)
        ) {
            ['message' => $msg, 'type' => $type] = gettype($this->flashMessages[$eventName]) === 'array' ?
                $this->flashMessages[$eventName] + ['type' => 'success'] :
                ['message' => $this->flashMessages[$eventName], 'type' => 'success'];

            \flash($msg, $type);
        }
    }

    protected function callMethodsAfterEvent(string $currentEvent)
    {
        if (count($this->flashMessages)!== 0 && key_exists($currentEvent, $this->flashMessages)) {
            $this->flashEventMessage($currentEvent);
        }
        if (count($this->afterEventMethods) !== 0) {
            foreach ($this->afterEventMethods as $method => $events) {

                $key = array_values(array_intersect(['all', $currentEvent], array_keys($events)))[0];
                call_user_func_array([$this,$method],$events[$key]);
            }
        }
    }

    protected function getModelIdentifier(string $identifier) : array
    {
        return [$this->getModelKeyName() => $identifier];
    }

    protected function getModelKeyName() : string
    {
        return (new($this->modelClass))->getRouteKeyName();
    }
}