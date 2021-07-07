<?php

namespace App\Service;

use App\News;
use App\Post;
use App\User;

class StatisticsService implements Indexable
{
    protected SimpleCacheService $simpleCacheService;

    public function __construct()
    {
        $this->simpleCacheService = SimpleCacheService::getInstance(__CLASS__);
    }

    public function index()
    {
        $queryData = function () {
            $statistics = [
                'postsCount' => Post::count(),
                'newsCount' => News::count(),
                'maxPostsCountAuthorName' => User::withCount('posts')
                    ->orderByDesc('posts_count')
                    ->first()['name'],
                'longestPost' => Post::where('published', 1)
                    ->select(\DB::raw('LENGTH(body) as len_body, title, slug'))
                    ->orderBy('len_body', 'desc')
                    ->first(),
                'shortestPost' => Post::where('published',1)
                    ->select(\DB::raw('LENGTH(body) as len_body, title, slug'))
                    ->orderBy('len_body', 'asc')
                    ->first(),
                'mostChangedPost' => Post::withCount('history')
                    ->orderByDesc('history_count')
                    ->first(),
                'mostCommentedPost' => Post::withCount('comments')
                    ->orderByDesc('comments_count')
                    ->first(),

            ];
            $activeUsers =  User::has('posts', '>', 1)->withCount('posts');
            $statistics['activeUsersAvgPosts'] = floor(\DB::table(\DB::raw("({$activeUsers->toSql()}) as active"))
                ->avg('posts_count'));

            return $statistics;
        };

        return $this->simpleCacheService->cacheQueryData($queryData);

    }

}