<?php


namespace App\Service;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class TagService implements RepositoryServiceable
{
    public function getFilterCallback()
    {
        return function ($query)
        {
            $models = config('tags.public_visible_related_models');
            return $this->getModelQueryFilter($models,$query);
        };
    }

    public function getModelQueryFilter($models, $query, $queryFilterName = 'queryFilter')
    {
        foreach ($models as $model => $options){
            if(method_exists( new $model(), $queryFilterName)) {
                $query = call_user_func_array([new $model(), $queryFilterName], [$query]);
            } else {
                $query = $query->orHas($options['relation']);
            }
        }
        return $query;
    }

    /**
     * @param FormRequest|Request $request
     */
    public function store(FormRequest|Request $request)
    {
        // TODO: Implement store() method.
    }

    /**
     * @param FormRequest|Request $request
     * @param $model
     */
    public function update(FormRequest|Request $request, $model)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $model
     */
    public function destroy($model)
    {
        // TODO: Implement destroy() method.
    }
}