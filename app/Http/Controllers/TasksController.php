<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->get();

        //dd($tasks);
        //return $tasks;
        //return view('welcome', ['name' => $name]); альтернативный синтаксис
        //return view('welcome')->with('name', $name);
        return view('tasks.index', compact( 'tasks'));
    }

    public function show(Task $task) { // laravel сопоставил $task с моделью Task и выбрал её по id
        //$task = Task::find($task);
        //dd($id);
        return view('tasks.show', compact('task'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store()
    {
       // dd(request()->all()); // метод выводит все поля формы, если хотим конкретное поле, то ->get('title) или request('title'); request(['title', 'body])

        // Создать новую задачу
//        $task = new Task;
//        $task->title = request('title');
//        $task->body = request('body');

        // Сохранить её в БД
        //$task->save();

        //Весь код выше можно заменить:
//        Task::create([
//            'title' => request('title'),
//            'body' => request('body')
//        ]);

        $this->validate(request(), [
            'title' => 'required',
            'body' => 'required'
        ]);
        Task::create(request()->all());


        // Редирект на список задач
        return redirect('/tasks');
    }

}
