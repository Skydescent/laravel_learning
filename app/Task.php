<?php

namespace App;

use App\Events\TaskCreated;

class Task extends \Illuminate\Database\Eloquent\Model
{
    //Защита от массового заполнения
    //public $fillable = ['title', 'body'];
    public $guarded = [];

    protected $dispatchesEvents = [
        'created' => TaskCreated::class,
    ];

    // чтобы переопределить поле по которому Laravel будет сопоставлять с переменной из пути(может быть и не id)
    public function getRouteKeyName()
    {
        return 'id';
    }

    public function scopeIncompleted($query) // Task::incomplete(false);
    {
        return $query->where('completed', 0);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function addStep($attributes)
    {
        return $this->steps()->create($attributes);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

}
