<?php

namespace App;

use App\Notifications\PostStatusChanged;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
      'password', 'remember_token',
    ];

    public function tasks()
    {
        return $this->hasMany(Task::class, 'owner_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'owner_id');
    }

}
