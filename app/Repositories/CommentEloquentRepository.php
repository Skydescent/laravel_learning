<?php

namespace App\Repositories;

use App\Comment;
use App\Commentable;
use App\Service\CacheService;
use App\Service\CommentsService;
use Illuminate\Contracts\Routing\UrlRoutable;

class CommentEloquentRepository extends EloquentRepository
{

    /**
     * @inheritDoc
     */
    protected static function setModel()
    {
        static::$model = Comment::class;
    }

    /**
     * @inheritDoc
     */
    protected function setCacheService()
    {
        $this->cacheService = CacheService::getInstance(static::$model);
    }

    /**
     * @inheritDoc
     */
    protected function setModelService()
    {
        $this->modelService = new CommentsService();
    }

    public function store($request, UrlRoutable|Commentable $model = null)
    {
        $this->modelService->store($request, $model);
        $this->cacheService->forgetMorphedModelRelation($model, ['relation' => 'comments']);
        $this->cacheService->flushCollections();
    }
}