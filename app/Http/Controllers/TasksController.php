<?php

namespace App\Http\Controllers;

use App\Events\TaskCreated;
use App\Tag;
use App\Task;

class TasksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:update,task')->except(['index', 'store', 'create']);
    }

    public function index()
    {
        //Все задачи от самых новых до самых старых с тэгами
        //$tasks = Task::where('owner_id', auth()->id())->with('tags')->latest()->get();
        $tasks = auth()->user()->tasks()->with('tags')->latest()->get();

        return view('tasks.index', compact( 'tasks'));
    }

    public function show(Task $task)
    {
        // laravel сопоставил $task с моделью Task и выбрал её по id
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

        $attributes['owner_id'] = auth()->id();

        $task = Task::create($attributes);

        //session()->flash('message', 'Задача успешно создана');
        flash('Задача успешно создана');


        // Редирект на список задач
        return redirect('/tasks');
    }

    public function edit(Task $task)
    {
        //abort_if($task->ownder_id !== auth()->id(), 403);
        //$this->authorize('update', $task);

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
        /** @var Collection $taskTags */
        $taskTags = $task->tags->keyBy('name');

        //Получаем из request строку с тэгами, преоразуем в массив, затем в коллекцию,
        // а затем в коллекции ключами делаем значения элементов коллекции
        $tags = collect(explode(',', request('tags')))->keyBy(function ($item) { return $item; });

        //Находим пересечение ключей тэгов из модели и из request, приводим к массиву с id
        $syncIds = $taskTags->intersectByKeys($tags)->pluck('id')->toArray();

        $tagsToAttach = $tags->diffKeys($taskTags);

        foreach ($tagsToAttach as $tag) {
            $tag = Tag::firstOrCreate(['name' => $tag]);
            $syncIds[] = $tag->id;
        }
//        $tagsToDetach = $taskTags->diffKeys($tags);
//
//        foreach ($tagsToAttach as $tag) {
//            //Статичный метод либо вернёт этот тэг, либо создаст новый
//            $tag = Tag::firstOrCreate(['name' => $tag]);
//            $task->tags()->attach($tag);
//        }
//
//        foreach ($tagsToDetach as $tag) {
//            $task->tags()->detach($tag);
//        }

        //Синхронизируем id тэгов в задаче с id из request
        $task->tags()->sync($syncIds);
        flash('Задача успешно обновлена');

        return redirect('/tasks');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        flash('Задача удалена', 'warning');
        return redirect('/tasks')->with('message', 'Задача удалена');
    }

}
