<?php


namespace App\Repositories;

use App\Post;
use App\Service\CacheService;
use App\Service\PostsService;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class PostEloquentRepository extends  EloquentRepository implements RepositoryTaggableInterface
{
    use HasTags;

    protected static function setModel()
    {
        self::$model = Post::class;
    }

    protected function setModelService()
    {
        $this->modelService = new PostsService();
    }

    protected function setCacheService()
    {
        $this->cacheService = CacheService::getInstance(static::$model);
    }

    public function adminIndex(Authenticatable|User|null $user, array $postfixes = [])
    {
       $paginator = (self::$model)::latest()
           ->with('owner')
           ->paginate(20);

       return $this->cacheService->cachePaginator($paginator, $user, $postfixes);
    }

    public function publicIndex(Authenticatable|User|null $user = null, array $postfixes = []) : mixed
    {
        $paginator = (self::$model)::latest()
            ->with('tags')
            ->where('published', 1)
            ->orWhere('owner_id', '=', auth()->id())
            ->simplePaginate(10);
        return $this->cacheService->cachePaginator($paginator, $user, $postfixes);
    }
}