<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Tag extends Model
{
    use HasFactory;

    protected $guarded = [];



    public function tasks()
    {
        return $this->morphedByMany(Task::class, 'taggable');
    }

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function news()
    {
        return $this->morphedByMany(News::class, 'taggable');
    }

    public function users()
    {
        return $this->morphedByMany(User::class, 'taggable');
    }

    //переопределяем метод, для того, чтобы ключём для маршрута стало
    //поле name из БД
    public function getRouteKeyName()
    {
        return 'name';
    }

    public static function tagsCloud(callable $filter)
    {
        return call_user_func($filter, (new static())->query())->get();
    }
}
