<?php


namespace App\Service;

use App\News;

class NewsService extends EloquentService
{

    protected static function setModelClass()
    {
        static::$modelClass = News::class;
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