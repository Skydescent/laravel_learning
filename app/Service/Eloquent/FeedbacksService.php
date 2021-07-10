<?php

namespace App\Service\Eloquent;

use App\Models\Feedback;
use App\Repositories\Eloquent\FeedbackRepository;
use App\Service\AdminServiceable;
use App\Models\User;

class FeedbacksService extends Service implements AdminServiceable
{

    public function adminIndex(User $user, array $postfixes = []) : mixed
    {
        $getIndex = function () {
          return  ($this->modelClass)::latest()->get();
        };

        $postfixes['panel'] = 'admin';

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
        $this->repository = FeedbackRepository::getInstance($this->modelClass);
    }

    public function store(array $attributes)
    {
        parent::store($attributes);
        flash('Ваше обращение получено, мы с вами свяжемся в ближайшее время!');
    }
}