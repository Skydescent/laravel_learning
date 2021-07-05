<?php

namespace App\Http\Middleware;

use App\Service\RepositoryServiceable;
use Closure;
use Illuminate\Http\Request;

class BindModelFromCache
{
    private RepositoryServiceable $modelService;

    public function initialize(string $modelClass)
    {
        $this->modelService = new $modelClass();
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $model
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $serviceClass, $requestFieldName): mixed
    {
        $this->modelService =  new $serviceClass();

        if ($identifier = $request->$requestFieldName) {

            $modelInstance = $this->modelService->find($identifier, cachedUser())?:$requestFieldName;
            $request->attributes->set($requestFieldName, $modelInstance);
        }

        return $next($request);
    }
}
