<?php

namespace App\Providers;

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
        $this->bindAllServices();
        $this->whenControllersNeedsGiveModelService();
    }

    protected function whenControllersNeedsGiveModelService()
    {
        $modelsServices = config('cache.model_services');

        foreach ($modelsServices as $interface => $reposForControllers) {
            foreach ($reposForControllers as $item) {
                $this->app->when($item['controllers'])
                    ->needs($interface)
                    ->give($item['service_closure']);
            }
        }
    }

    protected function bindAllServices()
    {
        $allServices = config('cache.all_services');

        foreach ($allServices as $interface => $service) {
            $this->app->singleton($interface, $service);
        }
    }
}
