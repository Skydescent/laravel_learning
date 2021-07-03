<?php

namespace App\Http\Middleware;

use App\Service\RepositoryServiceable;
use Closure;
use Illuminate\Http\Request;

class BindModelFromCache
{
    private RepositoryServiceable $modelService;


    public function __construct(RepositoryServiceable $modelService)
    {
        $this->modelService = $modelService;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $model
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $model): mixed
    {
        if ($identifier = $request->$model) {
            $modelInstance = $this->modelService->find($identifier, cachedUser())?:$model;
            $request->attributes->set($model, $modelInstance);
        }

        return $next($request);
    }
}
