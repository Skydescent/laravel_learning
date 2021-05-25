<?php


namespace App\Repositories;

use App\Cache\CacheEloquentWrapper;
use App\Service\CacheService;
use App\Post;
use App\User;

class PostEloquentRepository implements EloquentRepositoryInterface
{

    protected CacheService $cacheService;

    protected $identifier;

    const MODEL = Post::class;

    public function __construct()
    {
        $this->cacheService = new CacheService(self::MODEL);
    }

    public function adminAll($postfixes = [])
    {

    }

    public function publicAll(User|null $user = null, array $postfixes = [])
    {
        $queryData = function () {
            $collection = (self::MODEL)::latest()
                ->with('tags')
                ->where('published', 1)
                ->orWhere('owner_id', '=', auth()->id())
                ->simplePaginate(10);
            return CacheEloquentWrapper::wrapPaginator($collection, $this->cacheService);
        };
        return $this->cacheService->cache($queryData, $user, $postfixes);
    }

    public function find(array $identifier, User|null $user = null)
    {
        $queryData = function () use($identifier) {
            return CacheEloquentWrapper::wrapItem((self::MODEL)::firstWhere($identifier), $identifier, $this->cacheService);
        };
        return $this->cacheService->cache($queryData, $user, $identifier);
    }
}