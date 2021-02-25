<?php

namespace App\Providers;


use App\Channels\PushAllChannel;
use App\Service\TagService;
use App\View\Components\Alert;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        \Illuminate\Support\Collection::macro('toUpper', function() {
            return $this->map(function ($item) {
                return \Illuminate\Support\Str::upper($item);
            });
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layout.sidebar', function($view) {
            $filter = (new TagService())->getFilterCallback();
            $view->with('tagsCloud', \App\Tag::tagsCloud($filter));
        });


        Blade::component('alert', Alert::class);
        Blade::directive('datetime', function ($value) {
            return "<?php echo ($value)->format('d.m.Y') ?>";
        });
        Blade::if('admin', function ($user) { return $user && $user->isAdmin(); });
    }
}
