<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Notifications\PostStatusChanged;
use App\Post;
use App\Recipients\AdminRecipient;
use App\Service\PostsService;
use Illuminate\Http\Request;

class PostsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['create','update']);
        $this->middleware('can:update,post')->only(['edit', 'update', 'destroy']);
        $this->middleware('can:view,post')->only(['show']);
    }

    public function index()
    {
        $posts = Post::latest()->with('tags')->where('published', 1)->orWhere('owner_id', '=', auth()->id())->get();
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
        PostsService::postStoreOrUpdate(
            $request,
            null,
            'добавлена статья',
            'posts.show'
        );

        flash('Статья успешно добавлена');

        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        $isAdmin = false;
        return view('posts.edit', compact('post', 'isAdmin'));
    }

    public function update(PostStoreAndUpdateRequest $request, Post $post)
    {
        PostsService::postStoreOrUpdate(
            $request,
            $post,
            'обновлена статья',
            'posts.show'
        );
        flash('Статья успешно обновлена');

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        PostsService::postDestroy($post, 'удалена статья');
        flash('Статья удалена', 'warning');
        return redirect()->route('posts.index');
    }
}
