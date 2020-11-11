<?php

namespace App\Http\Controllers;

use App\Notifications\PostStatusChanged;
use App\Post;
use App\Recipients\AdminRecipient;
use App\Tag;
use App\User;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['create','update']);
        $this->middleware('can:update,post')->only(['edit', 'update', 'destroy']);
    }

    public function index()
    {
        $posts = Post::latest()->with('tags')->get();
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

    public function store()
    {
//        $attributes = $this->validate($request, [
//            'slug' => 'required|regex:/^[a-z0-9-_]+$/i|unique:posts',
//            'title' => 'required|between:5,100',
//            'short_text' => 'required|max:255',
//            'body' => 'required',
//            'published' => ''
//        ]);
        $attributes = Post::validate(request());

        $attributes['published'] = isset($attributes['published']) ? 1 : 0;
        $attributes['owner_id'] = auth()->id();

        $post = Post::create($attributes);

        flash('Статья успешно добавлена');

        $recipient = new AdminRecipient();
        $recipient->notify(new PostStatusChanged(
            'создана статья',
            $post->title,
            route('posts.show', ['post' => $post])
        ));

        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Post $post)
    {
//        $attributes = request()->validate( [
//            'slug' => 'required|regex:/^[a-z0-9-_]+$/i|unique:posts,slug,' . $post->id,
//            'title' => 'required|between:5,100',
//            'short_text' => 'required|max:255',
//            'body' => 'required',
//            'published' => ''
//        ]);
        $attributes = Post::validate(request(),$post->id);

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

        $recipient = new AdminRecipient();
        $recipient->notify(new PostStatusChanged(
            'обновлена статья',
            $post->title,
            route('posts.show', ['post' => $post])
        ));

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        flash('Статья удалена', 'warning');

        $recipient = new AdminRecipient();
        $recipient->notify(new PostStatusChanged(
            'удалена статья',
            $post->title
        ));

        return redirect()->route('posts.index');
    }
}
