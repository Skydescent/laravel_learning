<?php

namespace App\Http\Controllers;

use App\Tag;

class TagsController extends Controller
{
    public function index($model,Tag $tag)
    {
        $collection = $tag->$model()->with('tags')->get();
        return view($model . '.index', [$model => $collection]);
    }
}
