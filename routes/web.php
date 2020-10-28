<?php

use Illuminate\Support\Facades\Route;
use App\Task;

/**
 * GET /tasks (index)
 * GET /tasks/create (create)
 * GET /tasks/1 (show)
 * POST /tasks (store)
 * GET /tasks/1/edit (edit)
 * PATCH /tasks/1 (update)
 * DELETE /tasks/1 (destroy)
 */

Route::resource('/tasks', 'TasksController');

//Route::get('/tasks', 'TasksController@index')->name('tasks.index');
//Route::get('/tasks/create', 'TasksController@create')->name('tasks.create');
//Route::post('/tasks','TasksController@store')->name('tasks.store');
//Route::get('/tasks/{task}', 'TasksController@show')->name('tasks.show');
//Route::get('/tasks/{task}/edit','TasksController@edit' )->name('tasks.edit');
//Route::patch('/tasks/{task}', 'TasksController@update')->name('tasks.update');
//Route::delete('/tasks/{task}', 'TasksController@destroy')->name('tasks.delete');
//
//
//Route::get('/', 'PostsController@index')->name('posts.index');
//Route::view('/about', 'about')->name('about');
//Route::get('/contacts', 'FeedbacksController@create')->name('feedbacks.create');
//Route::get('/posts/create', 'PostsController@create')->name('posts.create');
//Route::post('/posts', 'PostsController@store')->name('posts.store');
//Route::get('/posts/{post}', 'PostsController@show')->name('posts.show');
//Route::get('/admin/feedbacks','FeedbacksController@index')->name('feedbacks.index');
//Route::post('/feedbacks','FeedbacksController@store')->name('feedbacks.store');
