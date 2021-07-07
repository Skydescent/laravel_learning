<?php


namespace App\Service;

use App\Comment;
use App\Commentable;
use App\Repositories\CommentEloquentRepository;
use App\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CommentsService extends EloquentService implements CommentsInterface
{
    public function storeComment(FormRequest|Request $request, Commentable $model = null, ?User $user = null)
    {
        $this->repository->store($request, $model, $user);
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
        $this->repository = CommentEloquentRepository::getInstance($this->modelClass);
    }
}