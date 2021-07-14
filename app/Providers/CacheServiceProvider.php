<?php

namespace App\Providers;


use App\Contracts\Service\CacheServiceContract;
use App\Contracts\Service\News\CreateNewsServiceContract;
use App\Contracts\Service\Post\CreatePostServiceContract;
use App\Contracts\Service\CreateStepServiceContract;
use App\Contracts\Service\Task\CreateTaskServiceContract;
use App\Contracts\Service\News\DestroyNewsServiceContract;
use App\Contracts\Service\Post\DestroyPostServiceContract;
use App\Contracts\Repository\FeedbackRepositoryContract;
use App\Contracts\Repository\NewsRepositoryContract;
use App\Contracts\Repository\PostRepositoryContract;
use App\Contracts\Repository\RepositoryCommentableContract;
use App\Contracts\Repository\RepositoryStepableContract;
use App\Contracts\Repository\StatisticsRepositoryContract;
use App\Contracts\Service\Tag\SyncTagsServiceContract;
use App\Contracts\Repository\TagRepositoryContract;
use App\Contracts\Service\Tag\TagsCloudServiceContract;
use App\Contracts\Repository\TaskRepositoryContract;
use App\Contracts\Service\News\UpdateNewsServiceContract;
use App\Contracts\Service\Post\UpdatePostServiceContract;
use App\Contracts\Service\Task\UpdateTaskServiceContract;
use App\Contracts\Repository\UserRepositoryContract;
use App\Http\Controllers\Public\CompletedTaskStepsController;
use App\Http\Controllers\Public\NewsCommentsController;
use App\Http\Controllers\Public\PostCommentsController;
use App\Http\Controllers\Public\TaskStepsController;
use App\Repositories\Eloquent\FeedbackRepository;
use App\Repositories\Eloquent\NewsRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Eloquent\StatisticsRepository;
use App\Repositories\Eloquent\TagRepository;
use App\Repositories\Eloquent\TaskRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Service\Cache\CacheService;
use App\Service\Eloquent\CreateNewsService;
use App\Service\Eloquent\CreatePostService;
use App\Service\Eloquent\CreateStepService;
use App\Service\Eloquent\CreateTaskService;
use App\Service\Eloquent\DestroyNewsService;
use App\Service\Eloquent\DestroyPostService;
use App\Service\Eloquent\SyncTagsService;
use App\Service\Eloquent\TagsCloudService;
use App\Service\Eloquent\UpdateNewsService;
use App\Service\Eloquent\UpdatePostService;
use App\Service\Eloquent\UpdateTaskService;
use App\Service\TagsInterface;
use App\Service\WrapService;
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
        $this->app->singleton(CacheServiceContract::class, CacheService::class);
        $this->app->singleton(SyncTagsServiceContract::class, SyncTagsService::class);
        $this->app->singleton(TagsCloudServiceContract::class, TagsCloudService::class);

        $this->app->singleton(PostRepositoryContract::class, PostRepository::class);
        $this->app->singleton(NewsRepositoryContract::class, NewsRepository::class);
        $this->app->singleton(TaskRepositoryContract::class, TaskRepository::class);
        $this->app->singleton(UserRepositoryContract::class, UserRepository::class);
        $this->app->singleton(TagRepositoryContract::class, TagRepository::class);
        $this->app->singleton(FeedbackRepositoryContract::class, FeedbackRepository::class);
        $this->app->singleton(StatisticsRepositoryContract::class, StatisticsRepository::class);

        $this->app->singleton(CreatePostServiceContract::class, CreatePostService::class);
        $this->app->singleton(CreateTaskServiceContract::class, CreateTaskService::class);
        $this->app->singleton(CreateNewsServiceContract::class, CreateNewsService::class);
        $this->app->singleton(CreateStepServiceContract::class, CreateStepService::class);

        $this->app->singleton(UpdatePostServiceContract::class, UpdatePostService::class);
        $this->app->singleton(UpdateTaskServiceContract::class, UpdateTaskService::class);
        $this->app->singleton(UpdateNewsServiceContract::class, UpdateNewsService::class);

        $this->app->singleton(DestroyPostServiceContract::class, DestroyPostService::class);
        $this->app->singleton(DestroyNewsServiceContract::class, DestroyNewsService::class);

        $this->app->when([TaskStepsController::class, CompletedTaskStepsController::class])
            ->needs(RepositoryStepableContract::class)
            ->give(TaskRepository::class);

        $this->app->when(NewsCommentsController::class)
            ->needs(RepositoryCommentableContract::class)
            ->give(NewsRepository::class);

        $this->app->when(PostCommentsController::class)
            ->needs(RepositoryCommentableContract::class)
            ->give(PostRepository::class);

        //$this->bindAllServices();
        //$this->whenControllersNeedsGiveModelService();
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
