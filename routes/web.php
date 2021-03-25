<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'PostsController@index');
Route::get('/statistics', 'StatisticsController@index')->name('statistics.index');

Route::get('/tags/{tag}', 'TagsController@index')->name('tags.cloud');
Route::resource('/tasks', 'TasksController');

Route::post('/tasks/{task}/steps', 'TaskStepsController@store');
Route::post('/completed-steps/{step}', 'CompletedStepsController@store');
Route::delete('/completed-steps/{step}', 'CompletedStepsController@destroy');


Route::resource('/posts', 'PostsController');
Route::resource('/news', 'NewsController')->only('index','show');

Route::view('/about', 'about')->name('about');
Route::get('/contacts', 'FeedbacksController@create')->name('feedbacks.create');
//Route::get('/feedbacks','FeedbacksController@index')->name('feedbacks.index');
Route::post('/feedbacks','FeedbacksController@store')->name('feedbacks.store');
Route::get('/greeting', function () {
    return 'Hello World';
});

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => ['auth', 'permissions']
], function () {
    Route::resource('/posts', 'Admin\PostsController')->only('index', 'update', 'edit', 'destroy');
    Route::resource('/news', 'Admin\NewsController');
    Route::get('/feedbacks','FeedbacksController@index')->name('feedbacks.index');
    Route::get('/reports', 'Admin\ReportsController@index')->name('reports.index');
    Route::get('/reports/{report}', 'Admin\ReportsController@make')->name('reports.make');
    Route::get('/send_report/{report}', 'Admin\ReportsController@sendReport')->name('reports.send');

});

Auth::routes();

Route::middleware('auth')->post('/companies', function () {
    $attributes = request()->validate(['name' => 'required']);
    $attributes['owner_id'] = auth()->id();

    \App\Company::create($attributes);
});

Route::get('/service', 'PushServiceController@form');
Route::post('/service', 'PushServiceController@send');

Route::middleware('auth')
    ->post('/posts/{post}/comment', 'PostCommentsController@store')
    ->name('post.comments.store');

Route::middleware('auth')
    ->post('/news/{news}/comment', 'NewsCommentsController@store')
    ->name('news.comments.store');