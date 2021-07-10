<?php

namespace App\Service\Eloquent;

use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\Eloquent\SimpleRepository;
use App\Service\Serviceable;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

abstract class Service implements Serviceable
{

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

    public function store(array $attributes)
    {
        $this->currentModel = $this->repository->store($attributes);
    }

    public function  update(array $attributes,string $identifier, ?User $user = null)
    {
        $this->currentModel = $this
            ->repository
            ->update(
                $attributes,
                $this->getModelIdentifier($identifier),
                $user
            );
    }

    public function destroy(string $identifier, ?User $user = null)
    {
        $this->currentModel = $this
            ->repository
            ->destroy(
                $this->getModelIdentifier($identifier),
                $user
            );
    }

    protected function getModelIdentifier(string $identifier) : array
    {
        return [$this->getModelKeyName() => $identifier];
    }

    protected function getModelKeyName() : string
    {
        return (new($this->modelClass))->getRouteKeyName();
    }

    public function getRepository(): EloquentRepositoryInterface
    {
        return $this->repository;
    }

    public function getCurrentModel() : Model
    {
        return $this->currentModel;
    }

}