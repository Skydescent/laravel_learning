<?php

namespace App\Service;

use App\Notifications\PostStatusChanged;
use App\Post;
use App\Recipients\AdminRecipient;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Log;

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

    protected static function setModelClass()
    {
       static::$modelClass = Post::class;
    }

    protected static function setRepository()
    {
        static::$repository = \App\Repositories\PostEloquentRepository::getInstance(static::$modelClass);
    }

    /**
     * @param string $message
     * @param string|null $routeName
     * @return PostsService
     */
    public function notifyAdmin(string $message, string|null $routeName = null): static
    {

        Log::info('PostsService@notifyAdmin ' . $this->currentModel->title);
        $route = $routeName ? route($routeName, ['post' => $this->currentModel]) : null;
        $recipient = new AdminRecipient();
        $recipient->notify(new PostStatusChanged(
            $message,
            $this->currentModel->title,
            $route
        ));
        return $this;
    }

    public function publicIndex(Authenticatable|User|null $user = null, array $postfixes = []) : mixed
    {
        $getIndex = function () {
            return (self::$modelClass)::latest()
                ->with('tags')
                ->where('published', 1)
                ->orWhere('owner_id', '=', auth()->id())
                ->simplePaginate(10);
        };

        return (static::$repository)->index($getIndex, $this->getModelKeyName(), $user,$postfixes);
    }
}