@extends('layout.app')

@section('title', 'Тэг: ' . $tag->name)

@section('app_content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Поиск по тэгу: {{$tag->name}}
        </h3>

{{--        TODO: --}}

        @foreach($publicModels as $model => $relation)

            <ul class="list-group">
                @foreach($tag[$relation] as $item)
                    <li class="list-group-item" href="#"><a>{{$item->title}}</a></li>
                @endforeach
            </ul>
            <hr>

        @endforeach
    </div>
@endsection
