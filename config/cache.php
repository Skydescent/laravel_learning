<?php

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
            \App\Post::class => [
                'tag' => 'posts',
                'isPersonal' => true,
                'relations' => [
                    'comments' => \App\Comment::class,
                    'tags' => \App\Tag::class,
                    'owner' => \App\User::class,
                    'history' => \App\PostHistory::class
                ],
            ],
            \App\News::class => [
                'tag' => 'news',
                'isPersonal' => false,
                'relations' => [
                    'comments' => \App\Comment::class,
                    'tags' => \App\Tag::class,
                ],
            ],
            \App\Tag::class => [
                'tag' => 'tags',
                'isPersonal' => true,
                'relations' => [
                    'tasks' => \App\Task::class,
                    'posts' => \App\Post::class,
                    'news' => \App\News::class,
                    'users' => \App\User::class
                ],

            ],
            \App\Comment::class => [
                'tag' => 'comments',
                'isPersonal' => false,
                'relations' => [
                    'author' => \App\User::class
                ]
            ]
        ],
        'allPrefix' => 'all',
        'personalKeyPrefix' => 'user',
        'ttl' => 300,
        'nameOfAllCacheKeysKey' => 'all_cache_keys',
    ]
];

//TODO: add relations configs to rest of models Classes