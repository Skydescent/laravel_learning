<?php

use App\Comment;
use App\Feedback;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\PostsController as AdminPostsController;
use App\Http\Controllers\CompletedStepsController;
use App\Http\Controllers\FeedbacksController;
use App\Http\Controllers\NewsCommentsController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PostCommentsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TasksController;
use App\Http\Controllers\TaskStepsController;
use App\Http\Middleware\BindModelFromCache;
use App\News;
use App\Post;
use App\PostHistory;
use App\Repositories\CommentableInterface;
use App\Repositories\CommentEloquentRepository;
use App\Repositories\EloquentRepositoryInterface;
use App\Repositories\FeedbackEloquentRepository;
use App\Repositories\NewsEloquentRepository;
use App\Repositories\PostEloquentRepository;
use App\Repositories\SimpleRepositoryInterface;
use App\Repositories\StatisticsRepository;
use App\Repositories\StepableInterface;
use App\Repositories\TaggableInterface;
use App\Repositories\StepEloquentRepository;
use App\Repositories\TaskEloquentRepository;
use App\Service\AdminServiceable;
use App\Service\FeedbacksService;
use App\Service\NewsService;
use App\Service\PostsService;
use App\Service\Serviceable;
use App\Service\StepsService;
use App\Service\TasksService;
use App\Step;
use App\Tag;
use App\Task;
use App\User;
use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Cache Store
    |--------------------------------------------------------------------------
    |
    | This option controls the default cache connection that gets used while
    | using this caching library. This connection is used when another is
    | not explicitly specified when executing a given caching function.
    |
    | Supported: "apc", "array", "database", "file",
    |            "memcached", "redis", "dynamodb"
    |
    */

    'default' => env('CACHE_DRIVER', 'file'),

    /*
    |--------------------------------------------------------------------------
    | Cache Stores
    |--------------------------------------------------------------------------
    |
    | Here you may define all of the cache "stores" for your application as
    | well as their drivers. You may even define multiple stores for the
    | same cache driver to group types of items stored in your caches.
    |
    */

    'stores' => [

        'apc' => [
            'driver' => 'apc',
        ],

        'array' => [
            'driver' => 'array',
            'serialize' => false,
        ],

        'database' => [
            'driver' => 'database',
            'table' => 'cache',
            'connection' => null,
        ],

        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],

        'memcached' => [
            'driver' => 'memcached',
            'persistent_id' => env('MEMCACHED_PERSISTENT_ID'),
            'sasl' => [
                env('MEMCACHED_USERNAME'),
                env('MEMCACHED_PASSWORD'),
            ],
            'options' => [
                // Memcached::OPT_CONNECT_TIMEOUT => 2000,
            ],
            'servers' => [
                [
                    'host' => env('MEMCACHED_HOST', '127.0.0.1'),
                    'port' => env('MEMCACHED_PORT', 11211),
                    'weight' => 100,
                ],
            ],
        ],

        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
        ],

        'dynamodb' => [
            'driver' => 'dynamodb',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'table' => env('DYNAMODB_CACHE_TABLE', 'cache'),
            'endpoint' => env('DYNAMODB_ENDPOINT'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Key Prefix
    |--------------------------------------------------------------------------
    |
    | When utilizing a RAM based store such as APC or Memcached, there might
    | be other applications utilizing the same cache. So, we'll specify a
    | value to get prefixed to all our keys so we can avoid collisions.
    |
    */

    'prefix' => env('CACHE_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_cache'),

    'cache_service' => [
        'map' => [
            Post::class => [
                'tag' => 'posts',
                'isPersonal' => true,
                'relations' => [
                    'comments' => Comment::class,
                    'tags' => Tag::class,
                    'owner' => User::class,
                    'history' => PostHistory::class
                ],
            ],
            News::class => [
                'tag' => 'news',
                'isPersonal' => false,
                'relations' => [
                    'comments' => Comment::class,
                    'tags' => Tag::class,
                ],
            ],
            Tag::class => [
                'tag' => 'tags',
                'isPersonal' => true,
                'relations' => [
                    'tasks' => Task::class,
                    'posts' => Post::class,
                    'news' => News::class,
                    'users' => User::class
                ],

            ],
            Comment::class => [
                'tag' => 'comments',
                'isPersonal' => false,
                'relations' => [
                    'author' => User::class
                ]
            ],
            Feedback::class => [
                'tag' => 'feedbacks',
                'isPersonal' => false,
            ],
            Task::class => [
                'tag' => 'tasks',
                'isPersonal' => true,
                'relations' => [
                    'steps' => Step::class,
                    'tags' => Tag::class,
                    'owner' => User::class,
                ],

            ],
            Step::class => [
                'tag' => 'steps',
                'isPersonal' => false,
                'relations' => [
                    'task' => Task::class
                ]
            ],
            User::class => [
                'tag' => 'users',
                'isPersonal' => false,
                'relations' => [
                    'tasks' => Task::class,
                    'posts' => Post::class,
                    'company' => \App\Company::class,
                    'tags' => Tag::class,
                    'comments' => Comment::class
                ]
            ],
        ],
        'simple_services' => [
            StatisticsRepository::class => [
                'tag' => 'statistics',
                'cached_models_collections' => [
                    User::class,
                    Post::class,
                    News::class,
                    Comment::class,
                    PostHistory::class
                ],
            ]
        ],
        'allPrefix' => 'all',
        'personalKeyPrefix' => 'user',
        'ttl' => 300,
    ],
    'model_services' => [
        Serviceable::class => [
            [
                'controllers' => [TasksController::class],
                'service_closure' => function () {
                    return new TasksService();
                }
            ],
//            [
//                'controllers' => [TasksController::class],
//                'repository_closure' => function () {
//                    return TaskEloquentRepository::getInstance();
//                }
//            ],
        ],
        AdminServiceable::class => [
            [
                'controllers' => [PostsController::class, AdminPostsController::class],
                'service_closure' => function () {
                    return new PostsService();
                }
            ],
            [
                'controllers' => [NewsController::class, AdminNewsController::class],
                'service_closure' => function () {
                    return new NewsService();
                }
            ],
            [
                'controllers' => [FeedbacksController::class],
                'service_closure' => function () {
                    return new FeedbacksService();
                }
            ],
        ],

        \App\Service\TagsInterface::class => [
            [
                'controllers' => [\App\Http\Controllers\TagsController::class],
                'service_closure' => function () {
                    return new \App\Service\TagService();
                }
            ]
        ],
        \App\Service\StepsInterface::class => [
            [
                'controllers' => [CompletedStepsController::class],
                'service_closure' => function () {
                    return new StepsService();
                }
            ]
        ]
    ]
];

