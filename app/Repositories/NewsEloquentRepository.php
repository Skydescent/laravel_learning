<?php


namespace App\Repositories;


use App\Cache\CacheEloquentWrapper;
use App\News;
use App\Service\EloquentCacheService;
use App\Service\NewsService;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class NewsEloquentRepository extends EloquentRepository implements TaggableInterface, CommentableInerface
{
    use HasTags, HasComments;

    /**
     *
     */
    protected static function setModel()
    {
        static::$model = News::class;
    }

    /**
     *
     */
    protected function setCacheService()
    {
        $this->cacheService = EloquentCacheService::getInstance(static::$model);
    }

    /**
     *
     */
    protected function setModelService()
    {
        $this->modelService = new NewsService();
    }

    public function publicIndex(Authenticatable|User|null $user = null, array $postfixes = []) : mixed
    {
        $paginator = (self::$model)::latest()->where('published', 1)->simplePaginate(10);
        return $this->cacheService->cachePaginator($paginator, $user, $postfixes);
    }

    public function adminIndex(Authenticatable|User|null $user, array $postfixes = [])
    {
        $paginator = (self::$model)::latest()->paginate(20);
        return $this->cacheService->cachePaginator($paginator, $user, $postfixes);
    }
}