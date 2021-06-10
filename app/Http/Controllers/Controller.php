<?php

namespace App\Http\Controllers;

use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected EloquentRepositoryInterface $modelInterface;

    public function __construct(EloquentRepositoryInterface $modelInterface)
    {
        $this->modelInterface = $modelInterface;
    }
}
