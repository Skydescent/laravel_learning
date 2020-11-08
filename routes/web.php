<?php

use Illuminate\Support\Facades\Route;
use App\Task;
Route::get('/', 'PostsController@index');

Route::get('/tasks/tags/{tag}', 'TagsController@index');

Route::resource('/tasks', 'TasksController');

Route::post('/tasks/{task}/steps', 'TaskStepsController@store');

Route::post('/completed-steps/{step}', 'CompletedStepsController@store');
Route::delete('/completed-steps/{step}', 'CompletedStepsController@destroy');


Route::resource('/posts', 'PostsController');

Route::view('/about', 'about')->name('about');
Route::get('/contacts', 'FeedbacksController@create')->name('feedbacks.create');
Route::get('/admin/feedbacks','FeedbacksController@index')->name('feedbacks.index');
Route::post('/feedbacks','FeedbacksController@store')->name('feedbacks.store');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

