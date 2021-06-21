<?php

namespace App\Service;

use App\Notifications\PostStatusChanged;
use App\Post;
use App\Recipients\AdminRecipient;

class PostsService extends EloquentService
{

    public array $flashMessages = [
        'update' => 'Статья успешно обновлена!',
        'store' => 'Статья успешно создана!',
        'destroy' => [
            'message' => 'Задача удалена!',
            'type' => 'warning'
        ],
    ];

    public array $afterEventMethods = [
        'notifyAdmin' => [
            'store' =>['добавлена статья', 'posts.show'],
            'update'=>['обновлена статья', 'posts.show'],
            'destroy'=>['статья удалена']
        ]
    ];

    /**
     *
     */
    protected static function setModelClass()
    {
       static::$modelClass = Post::class;
    }

    /**
     * @param null $request
     * @return array
     */
    protected function prepareAttributes($request = null): array
    {
        $attributes = $request->validated();
        $attributes['published'] = $attributes['published'] ?? 0;
        $attributes['owner_id'] = isset($this->model->owner) ? $this->model->owner->id :auth()->id();
        return $attributes;
    }

    /**
     * @param string $message
     * @param string|null $routeName
     */
    public function notifyAdmin(string $message, string|null $routeName = null): static
    {
        $route = $routeName ? route($routeName, ['post' => $this->model]) : null;
        $recipient = new AdminRecipient();
        $recipient->notify(new PostStatusChanged(
            $message,
            $this->model->title,
            $route
        ));
        return $this;
    }
}