<?php

namespace App\Service\Eloquent;

use App\Notifications\PostStatusChanged;
use App\Models\Post;
use App\Recipients\AdminRecipient;
use App\Repositories\Eloquent\PostRepository;
use App\Service\AdminServiceable;
use App\Models\User;

class PostsService extends Service implements AdminServiceable
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

    protected function setModelClass()
    {
       $this->modelClass = Post::class;
    }

    protected function setRepository()
    {
        $this->repository = PostRepository::getInstance($this->modelClass);
    }

    /**
     * @param string $message
     * @param string|null $routeName
     * @return PostsService
     */
    public function notifyAdmin(string $message, string|null $routeName = null): static
    {
        $route = $routeName ? route($routeName, ['post' => $this->currentModel]) : null;
        $recipient = new AdminRecipient();
        $recipient->notify(new PostStatusChanged(
            $message,
            $this->currentModel->title,
            $route
        ));
        return $this;
    }

    public function index(?User $user =  null, array $postfixes = []) : mixed
    {
        $getIndex = function () use ($user) {
            return ($this->modelClass)::latest()
                ->with('tags')
                ->where('published', 1)
                ->orWhere('owner_id', '=', $user->id)
                ->simplePaginate(10);
        };

        return $this->repository->index($getIndex, $this->getModelKeyName(), $user,$postfixes);
    }

    public function adminIndex(User $user, array $postfixes = [])
    {
        $getIndex = function () {
            return ($this->modelClass)::latest()->with('owner')->paginate(20);
        };

        $postfixes['panel'] = 'admin';

        return $this->repository->index($getIndex, $this->getModelKeyName(), $user, $postfixes);
    }

}