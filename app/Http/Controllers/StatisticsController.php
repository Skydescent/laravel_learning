<?php

namespace App\Http\Controllers;

use App\News;
use App\Post;
use App\User;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function index()
    {
        // TODO: в репозитории возможность передачи кастомных настроек напраямую в КэшСервис
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
        $statistics['activeUsersAvgPosts'] = floor(\DB::table(\DB::raw("({$activeUsers->toSql()}) as active"))->avg('posts_count'));

        return view('statistics.index', compact( 'statistics'));
    }
}
