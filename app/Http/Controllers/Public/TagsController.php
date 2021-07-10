<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Service\TagsInterface;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class TagsController extends Controller
{
    protected TagsInterface $tagService;

    public function __construct(TagsInterface $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index($name): Factory|View|Application
    {
        $tag = $this->tagService->find($name, cachedUser());
        $publicModels = config('tags.public_visible_related_models');
        return view('tags.index', compact('tag', 'publicModels'));
    }
}
