<?php


namespace App\Service;

use Illuminate\Contracts\Validation\ValidatesWhenResolved;
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
     * @param ValidatesWhenResolved|Request $request
     * @return mixed
     */
    public function store(ValidatesWhenResolved|Request $request)
    {
        // TODO: Implement store() method.
    }

    /**
     * @param ValidatesWhenResolved|Request $request
     * @param $model
     * @return mixed
     */
    public function update(ValidatesWhenResolved|Request $request, $model)
    {
        // TODO: Implement update() method.
    }

    /**
     * @param $model
     * @return mixed
     */
    public function destroy($model)
    {
        // TODO: Implement destroy() method.
    }
}