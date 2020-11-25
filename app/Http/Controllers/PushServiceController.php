<?php

namespace App\Http\Controllers;

use App\Service\Pushall;
use Illuminate\Http\Request;

class PushServiceController extends Controller
{
    public function form()
    {
        return view('service')
    }

    public function send(Pushall $pushall)
    {
        dd($pushall, \request()->all());
    }
}
