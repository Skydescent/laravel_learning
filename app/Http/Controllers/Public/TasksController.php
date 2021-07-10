<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskStoreAndUpdateRequest;
use App\Service\Serviceable;
use App\Service\TagsInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

class TasksController extends Controller
{
    /**
     * @var Serviceable
     */
    protected Serviceable $tasksService;

    /**
     * @var TagsInterface
     */
    protected TagsInterface $tagsService;

    /**
     * @param Serviceable $tasksService
     */
    public function __construct(Serviceable $tasksService)
    {
        $this->middleware('auth');
        $this
            ->middleware('model.from.cache:' . get_class($tasksService) . ',task')
            ->only(['show', 'edit', 'update', 'destroy']);
        $this->tasksService = $tasksService;
        $this->tagsService = $this->getTagService();
    }

    /**
     * @param Request|FormRequest|null $request
     * @return array
     */
    protected function prepareAttributes(Request|FormRequest $request = null): array
    {
        $attributes = $request->validated();
        $attributes['owner_id'] = isset($this->model->owner) ? $this->model->owner->id :cachedUser()->id;
        return $attributes;
    }

    public function index(): Factory|View|Application
    {
        $tasks = $this->tasksService->index(cachedUser());
        return view('tasks.index', compact( 'tasks'));
    }

    public function show(Request $request): Factory|View|Application
    {
        $task = $request->attributes->get('task');
        return view('tasks.show', compact('task'));
    }

    public function create(): Factory|View|Application
    {
        return view('tasks.create');
    }

    public function store(TaskStoreAndUpdateRequest $request): Redirector|Application|RedirectResponse
    {
        $this
            ->tagsService
            ->storeWithTagsSync(
                $this->tasksService,
                $this->prepareAttributes($request)
            );

        //$this->tasksService->store($this->prepareAttributes($request));
        return redirect('/tasks');
    }

    /**
     * @throws AuthorizationException
     */
    public function edit(Request $request): Factory|View|Application
    {
        $task = $request->attributes->get('task');
        $this->authorize('update', $task->model);

        return view('tasks.edit', compact('task'));
    }

    /**
     * @throws AuthorizationException
     */
    public function update(TaskStoreAndUpdateRequest $request): Redirector|Application|RedirectResponse
    {
        $task = $request->attributes->get('task');
        $this->authorize('update', $task->model);

        $this
            ->tagsService
            ->updateWithTagsSync(
                $this->tasksService,
                $this->prepareAttributes($request),
                $task->id,
                cachedUser()
            );

        //$this->tasksService->update($this->prepareAttributes($request), $id, cachedUser());;
        return redirect('/tasks');
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Request $request): Redirector|Application|RedirectResponse
    {
        $task = $request->attributes->get('task');
        $this->authorize('update', $task->model);

        $this->tasksService->destroy($task->id, cachedUser());

        return redirect('/tasks');
    }

}
