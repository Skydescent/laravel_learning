<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'PostsController@index');

Route::get('test', function () {
    $tasks = DB::table('users')
        ->sharedLock() //Блокировка модификации записей до тех пор, пока транзакция не завершена
        ->lockForUpdate() //Запрет на чтение записей до тех пор пока эти записи не будут обновлены
    ;
});
Route::get('/tags/{tag}', 'TagsController@index')->name('tags.cloud');

Route::resource('/tasks', 'TasksController');

Route::post('/tasks/{task}/steps', 'TaskStepsController@store');

Route::post('/completed-steps/{step}', 'CompletedStepsController@store');
Route::delete('/completed-steps/{step}', 'CompletedStepsController@destroy');


Route::resource('/posts', 'PostsController');

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
    Route::resource('posts', 'Admin\PostsController')->only('index', 'update', 'edit', 'destroy');
    Route::get('/feedbacks','FeedbacksController@index')->name('feedbacks.index');
});

Auth::routes();

Route::middleware('auth')->post('/companies', function () {
    $attributes = request()->validate(['name' => 'required']);
    $attributes['owner_id'] = auth()->id();

    \App\Company::create($attributes);
});

Route::get('/service', 'PushServiceController@form');
Route::post('/service', 'PushServiceController@send');
