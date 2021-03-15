<?php


namespace App\Service;

class TagService
{
    public function getFilterCallback()
    {
        return function ($query)
        {
            $models = config('tags.public_visible_related_models');
            return $this->getModelQueryFilter($models,$query);
        };
    }

    private function getModelQueryFilter($models, $query, $queryFilterName = 'queryFilter')
    {
        foreach ($models as $model => $relationMethod){
            if(method_exists( new $model(), $queryFilterName)) {
                $query = call_user_func_array([new $model(), $queryFilterName], [$query]);
            } else {
                $query = $query->orHas($relationMethod);
            }
        }
        return $query;
    }

}