<?php

namespace App;

class Task extends Model
{

    // чтобы переопределить поле по которому Laravel будет сопоставлять с переменной из пути(может быть и не id)
    public function getRouteKeyName()
    {
        return 'id';
    }


//    public static function completed()
//    {
//        return static::where('completed', 1)->get();
//    }

    public function scopeIncompleted($query) // Task::incomplete(false);
    {
        return $query->where('completed', 0);
    }
}
