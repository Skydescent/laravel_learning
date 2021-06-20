<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->whenControllersNeedsGiveRepository();
    }

    protected function whenControllersNeedsGiveRepository()
    {
        $repoLists = config('cache.cache_repositories');

        foreach ($repoLists as $interface => $reposForControllers) {
            foreach ($reposForControllers as $item) {
                $this->app->when($item['controllers'])
                    ->needs($interface)
                    ->give($item['repository_closure']);
            }
        }
    }
}
