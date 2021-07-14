<?php

namespace App\Providers;

use App\Contracts\Service\Tag\TagsCloudServiceContract;
use App\View\Components\Alert;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Collection::macro('toUpper', function() {
            return $this->map(function ($item) {
                return Str::upper($item);
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
            $tagsCloud = $this->app->get(TagsCloudServiceContract::class)->tagsCloud(getUserId());
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
            'tasks' => 'App\Models\Task',
            'posts' => 'App\Models\Post',
            'news' => 'App\Models\News',
            'users' => 'App\Models\User',
        ]);


    }
}
