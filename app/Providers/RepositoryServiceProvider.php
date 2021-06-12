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
        $repoInterface = config('cache.cache_repositories.interface_name');
        $controllerRepoMap = config('cache.cache_repositories.controller_repository_map');
        foreach ($controllerRepoMap as $item) {
            $this->app->when($item['controllers'])
                ->needs($repoInterface)
                ->give($item['repository_closure']);
        }
    }
}
