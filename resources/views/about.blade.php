@extends('layout.app')

@section('title', 'О нас')

@section('app_content')

    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            О нас
        </h3>

        Наш блог основан в таком то году....

        <hr>
        <a href="{{route('posts.index')}}">Вернуться на главную</a>
    </div>
@endsection
