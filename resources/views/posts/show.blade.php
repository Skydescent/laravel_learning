@extends('layout.master')

@section('title', $title)

@section('content')
<div class="col-md-8 blog-main">
    <h3 class="mb-2 font-italic">
        {{ $post->title }}
    </h3>
    @can('update', $post)
        <h5>
            <a href="{{ route('posts.edit', ['post' => $post]) }}" class="badge badge-primary">Изменить</a>
        </h5>
    @endcan
    <p class="blog-post-meta">{{$post->created_at}} </p>

    {{ $post->body }}

    <hr>
    <a href="{{route('posts.index')}}">Вернуться на главную</a>
</div>
@endsection
