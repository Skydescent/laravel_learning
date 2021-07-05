<?php


namespace App\Repositories;

use App\News;
use App\Service\EloquentCacheService;
use App\Service\NewsService;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class NewsEloquentRepository extends EloquentRepository //implements TaggableInterface, CommentableInterface
{
    //use HasTags, HasComments;

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