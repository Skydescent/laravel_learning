<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Notifications\PostStatusChanged;
use App\Post;
use App\Recipients\AdminRecipient;

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

    public function store(PostStoreAndUpdateRequest $request)
    {
        $attributes = $request->validated();

        $attributes['published'] = isset($attributes['published']) ? 1 : 0;
        $attributes['owner_id'] = auth()->id();

        $post = Post::create($attributes);

        $post->syncTags(request('tags'));

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

    public function update(PostStoreAndUpdateRequest $request, Post $post)
    {
        $attributes = $request->validated();

        $attributes['published'] = isset($attributes['published']) ? 1 : 0;

        $post->update($attributes);

        $post->syncTags(request('tags'));

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
