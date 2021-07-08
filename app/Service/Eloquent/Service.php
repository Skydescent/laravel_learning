<?php

namespace App\Service\Eloquent;

use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\Eloquent\SimpleRepository;
use App\Service\Serviceable;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use function flash;

abstract class Service implements Serviceable
{
    public array $flashMessages = [];

    public array $afterEventMethods = [];

    protected Model $currentModel;

    protected string $modelClass;

    protected EloquentRepositoryInterface $repository;

    public function __construct()
    {
        $this->setModelClass();
        $this->setRepository();
    }

    protected abstract function setModelClass();

    protected function setRepository()
    {
        $this->repository = SimpleRepository::getInstance($this->modelClass);
    }

    public function index()
    {
        $getIndex = function () {
            return ($this->modelClass)::all();
        };

        return $this->repository->index($getIndex, null, null, []);
    }

    public function find(string|array $identifier, ?User $user = null)
    {
        $identifier = gettype($identifier) == 'array' ?
            $identifier :
            $this->getModelIdentifier($identifier);
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

    public function  update(FormRequest|Request $request,string $identifier, ?User $user = null)
    {
        $this->currentModel = $this
            ->repository
            ->update(
                $request,
                $this->getModelIdentifier($identifier),
                $user
            );
        $this->callMethodsAfterEvent('update');
    }

    public function destroy(string $identifier, ?User $user = null)
    {
        $this->currentModel = $this
            ->repository
            ->destroy(
                $this->getModelIdentifier($identifier),
                $user
            );
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

            flash($msg, $type);
        }
    }

    protected function callMethodsAfterEvent(string $currentEvent, ...$args)
    {
        if (count($this->flashMessages)!== 0 && key_exists($currentEvent, $this->flashMessages)) {
            $this->flashEventMessage($currentEvent);
        }
        if (count($this->afterEventMethods) !== 0) {
            foreach ($this->afterEventMethods as $method => $events) {

                $key = array_values(array_intersect(['all', $currentEvent], array_keys($events)))[0];
                $arguments = array_merge($events[$key], $args);
                call_user_func_array([$this,$method],$arguments);
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

    public function getRepository()
    {
        return $this->repository;
    }

}