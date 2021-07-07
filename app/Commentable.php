<?php


namespace App;


interface Commentable extends \Illuminate\Contracts\Routing\UrlRoutable
{
    public function comments();
}