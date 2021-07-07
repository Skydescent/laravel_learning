<?php

namespace App\Service;

use App\Feedback;
use App\Repositories\FeedbackEloquentRepository;
use App\User;

class FeedbacksService extends EloquentService implements AdminServiceable
{

    public array $flashMessages = [
        'store' => 'Ваше обращение получено, мы с вами свяжемся в ближайшее время!',
    ];

    public function adminIndex(User $user, array $postfixes = []) : mixed
    {
        $getIndex = function () {
          return  ($this->modelClass)::latest()->get();
        };
        return $this->repository->index($getIndex, $this->getModelKeyName(), $user,$postfixes);
    }

    /**
     * @return mixed
     */
    protected function setModelClass() : void
    {
        $this->modelClass = Feedback::class;
    }

    /**
     * @return mixed
     */
    protected function setRepository() : void
    {
        $this->repository = FeedbackEloquentRepository::getInstance($this->modelClass);
    }
}