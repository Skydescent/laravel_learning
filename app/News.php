<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class News extends \App\Model
{
    use SynchronizeTags;

    protected $casts = [
        'published' => 'boolean'
    ];

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return string
     */
    public function getShortBodyAttribute(): string
    {
        return mb_substr($this->body, 0, 150) . '...';
    }

    public function queryFilter($query)
    {
        return $query->orWhereHas('news', function (Builder $subQuery) {
            $subQuery->where('published', 1);
        });
    }

    public function setSlugAttribute($value) {

        if (static::whereSlug($slug = \Str::of($value)->slug('_'))->exists()) {

            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }

    public function tags() : \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
