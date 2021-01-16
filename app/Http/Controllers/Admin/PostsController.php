<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Post;
use Illuminate\Http\Request;

class PostsController extends \App\Http\Controllers\PostsController
{
    public function index()
    {
        $posts = Post::latest()->with('tags')->get();
        return view('admin.posts.index', compact( 'posts'));
    }
}
