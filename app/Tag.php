<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded = [];

    public function tasks()
    {
        return $this->belongsToMany(Task::class);
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }

    //переопределяем метод, для того, чтобы ключём для маршрута стало
    //поле name из БД
    public function getRouteKeyName()
    {
        return 'name';
    }

    public static function tagsCloud($relatedWith)
    {
        return (new static)->has($relatedWith)->get();
    }
}
