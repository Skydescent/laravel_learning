<?php

use Illuminate\Support\Facades\Route;

Route::get('test', function () {
    // Использование тэгов кэша
    Cache::tags(['people', 'artists'])->put('John', $john, $seconds);
    Cache::tags(['people', 'authors'])->put('Anna', $anna, $seconds);

    // Чтобы получить ключи тэги обязательно использовать
    Cache::tags(['people', 'artists'])->get('John');
    Cache::tags(['people', 'authors'])->get('Anna');

    //Чтобы два раза не писать одно и то же, можено использовать метод remember
    $john = Cache::tags(['people', 'artists'])->remember('John', $john, $seconds);

    //Сбрасываем кэш по тэгам:
    Cache::tags(['people', 'artists'])->flush(); // сбросит кэш и для John и для Anna
    Cache::tags(['artists'])->flush(); //сбросит только Anna

});

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

Route::post('/chat', function () {
    broadcast(new \App\Events\ChatMessage(request('message'), auth()->user()))->toOthers();
})->middleware('auth');

Route::get('/send', function () {
    broadcast(new App\Events\EveryoneEvent());
    return response('Sent');
});

Route::get('/receiver', function () {
    return view('receiver');
});