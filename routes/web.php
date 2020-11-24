<?php

use Illuminate\Support\Facades\Route;

Route::view('/demo', 'demo');
//Route::view('/', 'welcome');

Route::get('/', 'PostsController@index');

Route::get('/{model}/tags/{tag}', 'TagsController@index')->name('tags.cloud');

Route::resource('/tasks', 'TasksController');

Route::post('/tasks/{task}/steps', 'TaskStepsController@store');

Route::post('/completed-steps/{step}', 'CompletedStepsController@store');
Route::delete('/completed-steps/{step}', 'CompletedStepsController@destroy');


//Route::resource('/posts', 'PostsController');

Route::view('/about', 'about')->name('about');
Route::get('/contacts', 'FeedbacksController@create')->name('feedbacks.create');
Route::get('/admin/feedbacks','FeedbacksController@index')->name('feedbacks.index');
Route::post('/feedbacks','FeedbacksController@store')->name('feedbacks.store');

Auth::routes();

