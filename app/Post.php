<?php

namespace App;

class Post extends \App\Model
{
    public function getRouteKeyName()
    {
        return 'slug';
    }

}
