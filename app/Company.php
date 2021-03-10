<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function logo()
    {
        return $this->morphOne(\App\Image::class, 'imageable');
    }
}
