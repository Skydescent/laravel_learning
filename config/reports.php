<?php

return [
    'reports' => [
         'total' => [
            'job' => \App\Jobs\ModelsCountReport::class,
            'title' => 'Итого',
            'reportable' => [
                'users' => [
                    'title' => 'Пользователей',
                    'data' => \App\User::class,
                ],
                'posts' => [
                    'title' => 'Статей',
                    'data' => \App\Post::class,
                ],
                'news' => [
                    'title' => 'Новостей',
                    'data' => \App\News::class
                ],
                'comments' => [
                    'title' => 'Комментариев',
                    'data' => \App\Comment::class
                ],
                'tags' => [
                    'title' => 'Тэгов',
                    'data' => \App\Tag::class,
                ],
            ],
        ]
    ],
];