<?php


namespace App\Repositories;

use App\Post;
use App\Service\EloquentCacheService;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class PostEloquentRepository extends  EloquentRepository //implements TaggableInterface, CommentableInterface
{
    //TODO: Now how use traits?
    //use HasTags, HasComments;

    /**
     * @param null $request
     * @return array
     */
    protected function prepareAttributes($request = null): array
    {
        $attributes = $request->validated();
        $attributes['published'] = $attributes['published'] ?? 0;
        $attributes['owner_id'] = isset($this->model->owner) ? $this->model->owner->id :auth()->id();
        return $attributes;
    }
}