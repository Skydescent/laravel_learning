@extends('layout.app')

@section('title', 'Новости')

@section('app_content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Наши новости:
        </h3>
        @foreach($news as $pieceOfNews)
            @include('news.item')
        @endforeach
        {{$news->links()}}
    </div>
    {{--TODO: add pagination--}}
@endsection
