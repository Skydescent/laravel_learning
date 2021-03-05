<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends \App\Model
{
    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return string
     */
    public function getShortBodyAttribute(): string
    {
        return mb_substr($this->body, 0, 150) . '...';
    }
}
