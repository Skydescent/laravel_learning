<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
