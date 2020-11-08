<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tag;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->where('published', 1)->with('tags')->get();
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
        $attributes = $this->validate($request, [
            'slug' => 'required|regex:/^[a-z0-9-_]+$/i|unique:posts',
            'title' => 'required|between:5,100',
            'short_text' => 'required|max:255',
            'body' => 'required',
            'published' => ''
        ]);

        $attributes['published'] = isset($attributes['published']) ? 1 : 0;

        Post::create($attributes);

        flash('Статья успешно добавлена');
        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Post $post)
    {
        $attributes = request()->validate( [
            'slug' => 'required|regex:/^[a-z0-9-_]+$/i|unique:posts,slug,' . $post->id,
            'title' => 'required|between:5,100',
            'short_text' => 'required|max:255',
            'body' => 'required',
            'published' => ''
        ]);

        $attributes['published'] = isset($attributes['published']) ? 1 : 0;

        $post->update($attributes);
        $taskTags = $post->tags->keyBy('name');
        $tags = collect(explode(',', request('tags')))->keyBy(function ($item) { return $item; });
        $syncIds = $taskTags->intersectByKeys($tags)->pluck('id')->toArray();
        $tagsToAttach = $tags->diffKeys($taskTags);
        foreach ($tagsToAttach as $tag) {
            $tag = Tag::firstOrCreate(['name' => $tag]);
            $syncIds[] = $tag->id;
        }
        $post->tags()->sync($syncIds);

        flash('Статья успешно обновлена');

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        flash('Статья удалена', 'warning');
        return redirect()->route('posts.index');
    }
}
