<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Функция composer принимает вид и колбэк функцию
        // Вид можно указать как маску, например * - все шаблоны
        view()->composer('layout.sidebar', function($view) {
            $relatedWith = isset($view->task) ? 'tasks' : 'posts';
            $view->with('modelAlias', $relatedWith);
            $view->with('tagsCloud', \App\Tag::tagsCloud($relatedWith));
        });
    }
}
