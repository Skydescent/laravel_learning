<?php

namespace App\Service\Eloquent;

use App\Models\News;
use App\Repositories\Eloquent\NewsRepository;
use App\Service\AdminServiceable;
use App\Models\User;

class NewsService extends Service implements AdminServiceable
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
        $this->repository = NewsRepository::getInstance($this->modelClass);
    }

    public function index(?User  $user = null, array $postfixes = []) : mixed
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

        $postfixes['panel'] = 'admin';

        return $this->repository->index($getIndex, $this->getModelKeyName(), $user,$postfixes);
    }

}