<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Post extends \App\Model
{
    use SynchronizeTags;

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

    /**
     * @return MorphToMany
     */
    public function tags() : MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * @return HasMany
     */
    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
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
