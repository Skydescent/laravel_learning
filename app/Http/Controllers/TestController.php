<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\WithFaker;


class TestController extends Controller
{
    use WithFaker;

    public function __construct()
    {
        $this->setUpFaker();
    }

    public function index()
    {
        return ;
    }

    public function test()
    {
//        $faker = $this->faker;
//        return $faker->regexify('^[a-z0-9-_]{15}$');
//        $arr = [];
//        try {
//            $p = $arr['pub'];
//            return $p;
//        } catch (\Exception $e) {}
//
//        return 'Всё ок';

        $arr = ['a' => 'a', 'b'=> 'b', 'c' => 'c'];
        $tags = ['tags' => $arr['a']];
        unset($arr['a']);

        var_dump($tags);
        var_dump($arr);
    }

}
