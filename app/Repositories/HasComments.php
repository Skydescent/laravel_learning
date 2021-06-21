<?php

namespace App\Repositories;

use App\Comment;
use App\Commentable;
use App\Service\EloquentCacheService;
use App\Service\CommentsService;
use Illuminate\Contracts\Routing\UrlRoutable;

trait HasComments
{
    protected EloquentCacheService $commentsCacheService;

    protected CommentableInterface $commentsService;

    protected function initializeStepServices()
    {
        $this->commentsCacheService = EloquentCacheService::getInstance(Comment::class);
        $this->commentsService = new CommentsService();
    }

    public function storeComment($request, UrlRoutable|Commentable $model = null)
    {
        $this->initializeStepServices();
        $this->commentsService->storeComment($request, $model);
        $this->commentsCacheService->forgetMorphedModelRelation($model, ['relation' => 'comments'], auth()->user());
    }

}