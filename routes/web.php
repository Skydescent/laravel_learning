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


Route::get('/tasks/tags/{tag}', 'TagsController@index');

Route::resource('/tasks', 'TasksController');

Route::post('/tasks/{task}/steps', 'TaskStepsController@store');

Route::post('/completed-steps/{step}', 'CompletedStepsController@store');
Route::delete('/completed-steps/{step}', 'CompletedStepsController@destroy');


//tasks

//tags



Route::get('/', 'PostsController@index')->name('posts.index');
Route::view('/about', 'about')->name('about');
Route::get('/contacts', 'FeedbacksController@create')->name('feedbacks.create');
Route::get('/posts/create', 'PostsController@create')->name('posts.create');
Route::post('/posts', 'PostsController@store')->name('posts.store');
Route::get('/posts/{post}', 'PostsController@show')->name('posts.show');
Route::get('/admin/feedbacks','FeedbacksController@index')->name('feedbacks.index');
Route::post('/feedbacks','FeedbacksController@store')->name('feedbacks.store');
