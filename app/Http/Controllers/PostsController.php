<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Post;
use App\Repositories\EloquentRepositoryInterface;
use App\Service\CacheService;
use App\Service\PostsService;


class PostsController extends Controller
{
    private $postService;

    public function __construct(EloquentRepositoryInterface $postInterface)
    {
        $this->middleware('auth')->only(['create','update']);
        $this->middleware('can:update,post')->only(['edit', 'update', 'destroy']);
        //$this->middleware('can:view,post')->only(['show']);
        $this->postInterface = $postInterface;
    }

    public function index()
    {

        $currentPage = request()->get('page',1);
        $posts = $this->postInterface->publicAll(auth()->user(), ['page' => $currentPage] );
            return view('posts.index', compact( 'posts'));
    }

    public function show($slug)
    {
        $post = $this->postInterface->find(['slug' => $slug]);
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(PostStoreAndUpdateRequest $request)
    {
        $this->postService
            ->setPost(new Post)
            ->storeOrUpdate($request->validated())
            ->notifyAdmin('добавлена статья', 'posts.show');

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
        $this->postService
            ->setPost($post)
            ->storeOrUpdate($request->validated())
            ->notifyAdmin('обновлена статья', 'posts.show');

        flash('Статья успешно обновлена');

        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        $this->postService
            ->setPost($post)
            ->destroy()
            ->notifyAdmin('удалена статья');
        flash('Статья удалена', 'warning');
        return redirect()->route('posts.index');
    }
}
