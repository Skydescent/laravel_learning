<?php

namespace App\Providers;


use App\Channels\PushAllChannel;
use App\Service\RepositoryService;
use App\View\Components\Alert;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
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
        //TODO: May be replace to CacheServiceProvider, or config map
        $this->app->when(\App\Http\Requests\PostStoreAndUpdateRequest::class)
            ->needs(\App\Service\RepositoryServiceable::class)
            ->give(\App\Service\PostsService::class);

        view()->composer('layout.sidebar', function($view) {
            $tagsCloud = (new \App\Service\TagService())->tagsCloud();
            $view->with('tagsCloud', $tagsCloud);
        });


        Blade::component('alert', Alert::class);
        Blade::directive('datetime', function ($value) {
            return "<?php echo ($value)->format('d.m.Y') ?>";
        });
        Blade::if('admin', function ($user) { return $user && $user->isAdmin(); });

        Paginator::defaultSimpleView('pagination::simple-default');
        Paginator::defaultView('pagination::bootstrap-4');

        Relation::morphMap([
            'tasks' => 'App\Task',
            'posts' => 'App\Post',
            'news' => 'App\News',
            'users' => 'App\User',
        ]);


    }
}
