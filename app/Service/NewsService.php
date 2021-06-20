<?php


namespace App\Service;


use App\News;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class NewsService extends EloquentService
{
    /**
     * @return mixed
     */
    protected static function setModelClass()
    {
        static::$modelClass = \App\News::class;
    }

    /**
     * @param null $request
     * @return array
     */
    protected function prepareAttributes($request = null): array
    {
        $attributes = $request->validated();
        $attributes['published'] = $attributes['published'] ?? 0;

        return $attributes;
    }
}