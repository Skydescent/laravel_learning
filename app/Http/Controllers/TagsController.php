<?php

namespace App\Http\Controllers;

use App\Service\TagsInterface;

class TagsController extends Controller
{
    protected TagsInterface $tagService;

    public function __construct(TagsInterface $tagService)
    {
        $this->tagService = $tagService;
    }

    public function index($name)
    {
        $tag = $this->tagService->find($name, cachedUser());
        $publicModels = config('tags.public_visible_related_models');
        return view('tags.index', compact('tag', 'publicModels'));
    }
}
