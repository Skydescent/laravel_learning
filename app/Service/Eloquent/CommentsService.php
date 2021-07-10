<?php

namespace App\Service\Eloquent;

use App\Models\Comment;
use App\Models\Commentable;
use App\Repositories\Eloquent\CommentRepository;
use App\Service\CommentsInterface;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CommentsService extends Service implements CommentsInterface
{
    public function storeComment(array $attributes, Commentable $model = null, ?User $user = null)
    {
        $this->repository->store($attributes, $model, $user);
    }

    /**
     * @return void
     */
    protected function setModelClass() : void
    {
        $this->modelClass = Comment::class;
    }

    protected function setRepository()
    {
        $this->repository = CommentRepository::getInstance($this->modelClass);
    }
}