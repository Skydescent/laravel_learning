<?php

namespace App;

class Step extends Model
{
    public $guarded = [];

    protected $touches = ['task'];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function complete($completed = true)
    {
        $this->update(['completed' => $completed]);
    }

    public function incomplete()
    {
        $this->complete(false);
    }
}
