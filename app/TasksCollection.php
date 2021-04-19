<?php


namespace App;


use Illuminate\Database\Eloquent\Collection;

class TasksCollection extends Collection
{
    public function allCompleted()
    {
        return $this->filter->isCompleted();
    }

    public function allNotCompleted()
    {
        return $this->filter->isNotCompleted();
    }

};