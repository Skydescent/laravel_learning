<?php

use Illuminate\Support\Facades\Route;
use App\Task;

Route::get('/', 'PostsController@index')->name('posts.index');
Route::view('/about', 'about')->name('about');
Route::get('/contacts', 'FeedbacksController@create')->name('feedbacks.create');
Route::get('/posts/create', 'PostsController@create')->name('posts.create');
Route::post('/posts', 'PostsController@store')->name('posts.store');
Route::get('/posts/{post}', 'PostsController@show')->name('posts.show');
Route::get('/admin/feedbacks','FeedbacksController@index')->name('feedbacks.index');
Route::post('/feedbacks','FeedbacksController@store')->name('feedbacks.store');
