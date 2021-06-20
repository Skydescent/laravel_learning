<?php


namespace App\Repositories;

use App\Post;
use App\Service\EloquentCacheService;
use App\Service\PostsService;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;

class PostEloquentRepository extends  EloquentRepository implements TaggableInterface, CommentableInerface
{
    use HasTags, HasComments;

    protected static function setModel()
    {
        static::$model = Post::class;
    }

    protected function setModelService()
    {
        $this->modelService = new PostsService();
    }

    protected function setCacheService()
    {
        $this->cacheService = EloquentCacheService::getInstance(static::$model);
    }

    public function adminIndex(Authenticatable|User|null $user, array $postfixes = [])
    {
       $paginator = (static::$model)::latest()
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