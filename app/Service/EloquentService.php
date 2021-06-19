<?php

namespace App\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

abstract class EloquentService implements RepositoryServiceable
{
    public array $flashMessages = [];

    protected Model $model;

    protected static string $modelClass;

    protected abstract static function setModelClass();

    protected abstract function prepareAttributes($request = null): array;

    public function __construct()
    {
        static::setModelClass();
    }

    public function setModel(Model|null $model = null): EloquentService
    {
        $this->model = $model ?? new static::$modelClass();

        return $this;
    }

    public function getModel(): Model
    {
        return $this->model;
    }

    protected function storeOrUpdate(FormRequest|Request $request, Model|null $model = null): EloquentService
    {
        $attributes = $this->prepareAttributes($request);

        $this->setModel($model);

        $tags = $attributes['tags'] ?? null;
        unset($attributes['tags']);

        $model = (static::$modelClass)::updateOrCreate(['id' => $this->model->id], $attributes);

        $this->setModel($model);

        if ($this->getModel() instanceof \App\Taggable) {
            $this->model->syncTags($tags);
        }

        return $this;
    }

    public function store(FormRequest|Request $request)
    {
        $this
            ->storeOrUpdate($request)
            ->flashEventMessage('store');
    }

    public function  update(FormRequest|Request $request, Model $model)
    {
        $this
            ->storeOrUpdate($request, $model)
            ->flashEventMessage('update');
    }

    public function destroy(Model $model)
    {
        $this
            ->setModel($model)
            ->destroyModel()
            ->flashEventMessage('destroy');
    }

    protected function destroyModel()
    {
        $this->model->delete();
        return $this;
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
}