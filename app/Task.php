<?php

namespace App;

use App\Events\TaskCreated;
use App\Events\TaskUpdated;
use Illuminate\Database\Eloquent\Collection;

class Task extends Model implements Taggable
{
    use SynchronizeTags;

    //Защита от массового заполнения
    //public $fillable = ['title', 'body'];
    public $guarded = [];

    protected static function boot()
    {
        parent::boot();
        static::created(function() {
           \Cache::tags(['tasks'])->flush();
        });
        static::updated(function() {
            \Cache::tags(['tasks'])->flush();
        });
        static::deleted(function() {
            \Cache::tags(['tasks'])->flush();
        });
    }

    protected $dispatchesEvents = [
        'created' => TaskCreated::class,
        'updated' => TaskUpdated::class,
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
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function addStep($attributes)
    {
        return $this->steps()->create($attributes);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function isCompleted()
    {
        return (bool) $this->completed;
    }

    public function isNotCompleted()
    {
        return !$this->completed;
    }

    public function newCollection(array $models = [])
    {
        return new TasksCollection($models);
    }

}

