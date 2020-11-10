<?php

namespace App;

class Post extends \App\Model
{
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}
