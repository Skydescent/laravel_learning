<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    public $guarded = [];
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
