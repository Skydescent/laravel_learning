<?php

namespace App;

use App\Events\TaskCreated;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    //Защита от массового заполнения
    //public $fillable = ['title', 'body'];
    public $guarded = [];

//    protected $appends = [
//        'double_type'
//    ];

//    protected $dispatchesEvents = [
//        'created' => TaskCreated::class,
//    ];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('onlyNew', function(\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->new();
        }); //Если логика фильтрации сложная, то можно вынести в класс, наследующий Eloquent/Scope
    }

    public function getTypeAttribute($value)
    {
        return ucfirst($value);
    }

    public function getDoubleTypeAttribute()
    {
        return $this->attributes['double_type'] = str_repeat($this->type, 2);
    }

    public function setTypeAttribute($value)
    {
        $this->attributes['type'] = ucfirst(strtolower($value));
    }


    // чтобы переопределить поле по которому Laravel будет сопоставлять с переменной из пути(может быть и не id)
    public function getRouteKeyName()
    {
        return 'id';
    }

    public function scopeIncompleted($query) // Task::incomplete(false);
    {
        return $query->where('completed', 0);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeNew($query)
    {
        return $query->ofType('new');
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
        return new class($models) extends Collection {
            public function allCompleted()
            {
                return $this->filter->isCompleted();
            }

            public function allNotCompleted()
            {
                return $this->filter->isNotCompleted();
            }

//            public static function range($from, $to)
//            {
//                // TODO: Implement range() method.
//            }
//
//            public function chunkWhile(callable $callback)
//            {
//                // TODO: Implement chunkWhile() method.
//            }
        };
    }

}

