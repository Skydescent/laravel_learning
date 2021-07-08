<?php


namespace App\Repositories\Eloquent;

class NewsRepository extends Repository
{
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