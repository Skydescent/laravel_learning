<?php


namespace App;


class Model extends \Illuminate\Database\Eloquent\Model
{
    //Поля разрешённые к массовуму заполнению
    //public $fillable = ['title', 'body'];

    //Поля защищённые от массового заполнения
    public $guarded = [];
}
