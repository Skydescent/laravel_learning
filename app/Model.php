<?php


namespace App;


use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use HasFactory;

    //Поля разрешённые к массовуму заполнению
    //public $fillable = ['title', 'body'];

    //Поля защищённые от массового заполнения
    public $guarded = [];

}
