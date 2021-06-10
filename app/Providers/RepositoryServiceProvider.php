<?php

namespace App\Providers;

use App\Http\Controllers\Admin\PostsController as AdminPostsController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\NewsCommentsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PostCommentsController;
use App\Http\Controllers\PostsController;
use App\Repositories\CommentEloquentRepository;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\PostEloquentRepository;
use App\Repositories\NewsEloquentRepository;
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

        $this->app->when([PostsController::class, AdminPostsController::class])
            ->needs(EloquentRepositoryInterface::class)
            ->give(function () {
                return PostEloquentRepository::getInstance();
            });

        $this->app->when([NewsController::class, AdminNewsController::class])
            ->needs(EloquentRepositoryInterface::class)
            ->give(function () {
                return NewsEloquentRepository::getInstance();
            });

        $this->app->when([NewsCommentsController::class, PostCommentsController::class])
            ->needs(EloquentRepositoryInterface::class)
            ->give(function () {
                return CommentEloquentRepository::getInstance();
            });

    }
}
