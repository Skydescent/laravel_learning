<?php

namespace App\Http\Controllers;

use App\Service\Pushall;
use Illuminate\Http\Request;

class PushServiceController extends Controller
{
    public function form()
    {
        return view('service');
    }

    public function send()
    {
        $data = \request()->validate([
            'text' => 'required|max:80',
            'title' => 'required|max:500'
        ]);

        push_all($data['title'], $data['text']);

        //Флеш уведомление
        flash('Сообщение успешно отправлено');

        //Редирект на предыдущую страницу
        return back();
    }
}
