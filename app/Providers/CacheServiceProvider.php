<?php

namespace App\Providers;

use App\Service\TagService;
use App\Service\TagsInterface;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
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
        $this->whenControllersNeedsGiveModelService();
    }

    protected function whenControllersNeedsGiveModelService()
    {
        $repoLists = config('cache.model_services');

        foreach ($repoLists as $interface => $reposForControllers) {
            foreach ($reposForControllers as $item) {
                $this->app->when($item['controllers'])
                    ->needs($interface)
                    ->give($item['service_closure']);
            }
        }
    }
}
