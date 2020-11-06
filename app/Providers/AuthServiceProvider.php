<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \App\Task::class => \App\Policies\TaskPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @param Gate $gate
     * @return void
     */
    public function boot(\Illuminate\Contracts\Auth\Access\Gate $gate)
    {
        $this->registerPolicies();

        $gate->before(function ($user) {
            if ($user->id == 2) {
                return true;
            }
        });
    }
}
