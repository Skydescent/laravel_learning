<?php

namespace App\Http\Controllers;

use App\Service\Indexable;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class StatisticsController extends Controller
{
    protected Indexable $statisticsService;

    public function __construct(Indexable $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function index(): Factory|View|Application
    {
        $statistics = $this->statisticsService->index();

        return view('statistics.index', compact( 'statistics'));
    }
}
