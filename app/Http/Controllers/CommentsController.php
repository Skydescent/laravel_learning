<?php

namespace App\Http\Controllers;

use App\Model;
use App\Post;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\RedirectResponse;

class CommentsController extends Controller
{

    protected $model;

    /**
     * CommentsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth')->only(['store']);
        $this->model = Relation::getMorphedModel(
            request()->route()->parameter('model')
        );
    }

    /**
     * @param Request
     * @return RedirectResponse
     */
    public function store() : RedirectResponse
    {
        $slug = \request('slug');
        $attributes = \request()->validate([
           'body' => 'required',
        ]);
        $attributes['author_id'] = auth()->id();

        $item = ($this->model)::firstWhere('slug', $slug);

        $item->comments()->create($attributes);

        return back();
    }
}
