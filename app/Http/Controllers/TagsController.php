<?php

namespace App\Http\Controllers;

use App\Tag;

class TagsController extends Controller
{
    public function index(Tag $tag)
    {
        $publicModels = config('tags.public_visible_related_models');
        return view('tags.index', compact('tag', 'publicModels'));
    }
}
