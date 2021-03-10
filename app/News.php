<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends \App\Model
{
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

    public function setSlugAttribute($value) {

        if (static::whereSlug($slug = \Str::of($value)->slug('_'))->exists()) {

            $slug = $this->incrementSlug($slug);
        }

        $this->attributes['slug'] = $slug;
    }
}
