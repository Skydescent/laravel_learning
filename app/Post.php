<?php

namespace App;

use App\Scopes\CacheScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use function Symfony\Component\String\s;

class Post extends \App\Model implements Commentable, Taggable
{
    use SynchronizeTags;


    protected static function booted()
    {
        static::updated(function (\App\Post $post) {
            broadcast(new \App\Events\PostUpdated($post, cachedUser(\request())));
        });
        static::created(function (\App\Post $post) {
            event(new \App\Events\PostCreated($post));
        });
    }

    protected static function boot()
    {
        parent::boot();

            static::updating(function($post) {
		    $post->history()->attach(auth()->id(),
                [
                    'changed_fields' =>json_encode(array_keys($post->getDirty())),
                ]
            );
        });
    }

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function queryFilter($query)
    {
            return $query->orWhereHas('posts', function (Builder $subQuery) {
                $subQuery
                    ->where('published', 1)
                    ->orWhere('owner_id', '=', auth()->id());
            });
    }

    public function postQueryFilter($query)
    {
        return $query
            ->where('published', 1)
            ->orWhere('owner_id', '=', cachedUser(\request())->id);
    }

    /**
     * @return MorphToMany
     */
    public function tags() : MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function comments()
    {
        return $this->morphMany(\App\Comment::class, 'commentable' );
    }

    /**
     * @return BelongsTo
     */
    public function owner() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany
     */
    public function history() : BelongsToMany
    {
        return $this
            ->belongsToMany(\App\User::class, 'post_histories')
            ->withPivot(['changed_fields'])
            ->withTimestamps();
    }

    /**
     * @param $attributes
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addComment($attributes) : \Illuminate\Database\Eloquent\Model
    {
        return $this
            ->comments()
            ->create(array_merge(
                $attributes,
                ['post_id' => $this->id]
            ));
    }
}
