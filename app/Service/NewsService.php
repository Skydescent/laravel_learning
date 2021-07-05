<?php


namespace App\Service;

use App\News;
use App\Repositories\NewsEloquentRepository;
use App\User;

class NewsService extends EloquentService
{

    protected function setModelClass()
    {
        $this->modelClass = News::class;
    }

    /**
     * @return mixed
     */
    protected function setRepository() : void
    {
        $this->repository = NewsEloquentRepository::getInstance($this->modelClass);
    }

    public function publicIndex(User $user, array $postfixes = []) : mixed
    {
        $getIndex = function () {
            return ($this->modelClass)::latest()->where('published', 1)->simplePaginate(10);
        };

        return $this->repository->index($getIndex, $this->getModelKeyName(), $user,$postfixes);
    }

    public function adminIndex(User $user, array $postfixes = [])
    {
        $getIndex = function () {
            return ($this->modelClass)::latest()->paginate(20);
        };

        return $this->repository->index($getIndex, $this->getModelKeyName(), $user,$postfixes);
    }

}