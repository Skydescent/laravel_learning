<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
     * TestController constructor.
     */
    public function __construct()
    {
        $this->middleware('test', ['except' => ['index']]);
    }

    public function index()
    {
        return 'hello';
    }

    public function show()
    {
        return 'show';
    }

}
