<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Post;
use App\Service\PostsService;
use Illuminate\Http\Request;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->with('owner')->get();
        return view('admin.posts.index', compact( 'posts'));
    }

    public function update(PostStoreAndUpdateRequest $request, Post $post)
    {
        PostsService::postStoreOrUpdate($request,$post);
        return redirect()->route('admin.posts.index');
    }

    public function edit(Request $request, Post $post)
    {
        //$extendedView = auth()->user()->isAdmin() ? ''
        dd(auth()->user());
    }
}
