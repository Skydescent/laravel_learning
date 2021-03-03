<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends \App\Model
{
    use SynchronizeTags;

    protected static function boot()
    {
        parent::boot();

        static::updating(function($post) {
            $after = $post->getDirty();
		    $post->history()->attach(
                [
                    'post_id' => $post->id,
//                    'author_id' => auth()->id(),
//                    'before' =>json_encode(\Arr::only(
//                        $post->fresh()->toArray(),
//                        array_keys($after)
//                    )),
//                    'after' => json_encode($after),
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
     * @return BelongsToMany
     */
    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
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
            ->belongsToMany(
                \App\User::class,
                'post_histories',
                'author_id',
                'id'
            )
            ->withPivot(['before', 'after'])
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
