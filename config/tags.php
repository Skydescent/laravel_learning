<?php

return [
    'public_visible_related_models' => [
        'App\Post' => [
            'title' => 'Статьи',
            'relation' => 'posts',
            'showView' => 'posts.show',
            'item' => 'post',

        ],
        'App\News' => [
            'title' => 'Новости',
            'relation' => 'news',
            'showView' => 'news.show',
            'item' => 'news',

        ],
    ],
];
