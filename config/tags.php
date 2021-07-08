<?php

return [
    'public_visible_related_models' => [
        'App\Models\Post' => [
            'title' => 'Статьи',
            'relation' => 'posts',
            'showView' => 'posts.show',
            'item' => 'post',
        ],
        'App\Models\News' => [
            'title' => 'Новости',
            'relation' => 'news',
            'showView' => 'news.show',
            'item' => 'news',
        ],
    ],
];
