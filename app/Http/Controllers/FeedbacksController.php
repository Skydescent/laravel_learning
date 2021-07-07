<?php

namespace App\Http\Controllers;

use App\Service\AdminServiceable;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class FeedbacksController extends Controller
{

    /**
     * @var AdminServiceable
     */
    protected AdminServiceable $feedbacksService;

    /**
     * @param AdminServiceable $feedbacksService
     */
    public function __construct(AdminServiceable $feedbacksService)
    {
        $this->feedbacksService = $feedbacksService;
    }

    /**
     * @return Application|Factory|View
     */
    public function index(): View|Factory|Application
    {
        $feedbacks = $this->feedbacksService->adminIndex(cachedUser());
        return view('admin.feedbacks.index', compact( 'feedbacks'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create(): View|Factory|Application
    {
        return view('feedbacks.create');
    }

    /**
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request): Redirector|RedirectResponse|Application
    {
        $this->feedbacksService->store($request);

        return redirect('/');
    }
}
