@extends('layout.app')

@section('title', $news->title)

@section('app_content')
<div class="col-md-8 blog-main">
    <h3 class="mb-2 font-italic">
        {{ $news->title }}
    </h3>
    <p class="blog-post-meta">{{$news->created_at}} </p>

    {{ $news->body }}

    <hr>
    <a href="{{route('news.index')}}">Вернуться к новостям</a>
</div>
@endsection
