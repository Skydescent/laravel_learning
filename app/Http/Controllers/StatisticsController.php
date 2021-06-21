<?php

namespace App\Http\Controllers;

use App\Repositories\SimpleRepositoryInterface;

class StatisticsController extends Controller
{
    protected SimpleRepositoryInterface $repositoryInterface;

    public function __construct(SimpleRepositoryInterface $repositoryInterface)
    {
        $this->repositoryInterface = $repositoryInterface;
    }

    public function index()
    {
        $statistics = $this->repositoryInterface->index();

        return view('statistics.index', compact( 'statistics'));
    }
}
