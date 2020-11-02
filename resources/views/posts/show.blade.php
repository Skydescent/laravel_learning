@extends('layout.master')

@section('title', $title)

@section('content')
<div class="col-md-8 blog-main">
    <h3 class="pb-4 mb-4 font-italic border-bottom">
        {{ $post->title }}
    </h3>
    <p class="blog-post-meta">{{$post->created_at}} </p>

    {{ $post->body }}

    <hr>
    <a href="{{route('posts.index')}}">Вернуться на главную</a>
</div>
@endsection
