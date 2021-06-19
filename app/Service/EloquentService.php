<?php

namespace App\Service;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

abstract class EloquentService implements RepositoryServiceable
{
    public array $flashMessages = [];

    public array $afterEventMethods = [];

    protected Model $model;

    protected static string $modelClass;

    protected abstract static function setModelClass();

    protected abstract function prepareAttributes($request = null): array;

    public function __construct()
    {
        static::setModelClass();
    }

    public function __call($method,$arguments) {
        Log::info('before if in EloquentService@__call(), method: ' . $method);
        if(in_array($method, self::ALLOWED_EVENTS)) {
            Log::info('in EloquentService@__call(), method: ' . $method);
            if (count($this->flashMessages)!== 0 && key_exists($method, $this->flashMessages)) {
                $this->flashEventMessage($method);
            }
            $this->callMethodsAfterEvent($method);
        }
        if (method_exists($this, $method)) return call_user_func_array([$this,$method],$arguments);
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
            ->callMethodsAfterEvent('store');
    }

    public function  update(FormRequest|Request $request, Model $model)
    {
        $this
            ->storeOrUpdate($request, $model)
            ->callMethodsAfterEvent('update');
    }

    public function destroy(Model $model)
    {
        $this
            ->setModel($model)
            ->destroyModel()
            ->callMethodsAfterEvent('destroy');
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
}