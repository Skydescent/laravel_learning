<?php


namespace App\Service;


use Illuminate\Database\Eloquent\Builder;

class TagService
{
    public function getFilterCallback()
    {
        return function ($query)
        {
            return $this->postsQueryFilter($query);
        };
    }

    private function postsQueryFilter($query)
    {
           return $query->whereHas('posts', function (Builder $subQuery) {
                $subQuery->where('published', 1)->orWhere('owner_id', '=', auth()->id());
            });
    }
}