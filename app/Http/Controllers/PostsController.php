<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->get();
        return view('posts.index', compact( 'posts'));
    }

    public function show(Post $post)
    {
        $title = $post->title;
        return view('posts.show', compact('post', 'title'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $input = $this->validate($request, [
            'slug' => 'required|regex:/^[a-z0-9-_]+$/i|unique:posts',
            'title' => 'required|between:5,100',
            'short_text' => 'required|max:255',
            'body' => 'required',
            'published' => ''
        ]);

        $input['published'] = isset($input['published']) ? 1 : 0;

        Post::create($input);

        return redirect('/');
    }
}
