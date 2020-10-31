<?php

namespace App\Http\Controllers;

use App\Task;

class TasksController extends Controller
{
    public function index()
    {
        //Все задачи от самых новых до самых старых с тэгами
        $tasks = Task::with('tags')->latest()->get();
        return view('tasks.index', compact( 'tasks'));
    }

    public function show(Task $task) { // laravel сопоставил $task с моделью Task и выбрал её по id
        return view('tasks.show', compact('task'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store()
    {
        $attributes = request()->validate( [
            'title' => 'required',
            'body' => 'required'
        ]);
        Task::create($attributes);


        // Редирект на список задач
        return redirect('/tasks');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Task $task)
    {
        $attributes = request()->validate( [
            'title' => 'required',
            'body' => 'required'
        ]);

        //$task->update(request(['title', 'body']));
        $task->update($attributes);

        //Получаем текущую коллекцию тэгов и ключами делаем поля name
        $taskTags = $task->tags->keyBy('name');

        //Получаем из request строку с тэгами, преоразуем в массив, затем в коллекцию,
        // а затем в коллекции ключами делаем значения элементов коллекции
        $tag = collect(explode(',', request('tags')))->keyBy(function ($item) { return $item; });

        return redirect('/tasks');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect('/tasks');
    }

}
