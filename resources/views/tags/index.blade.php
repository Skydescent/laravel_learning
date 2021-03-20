@extends('layout.app')

@section('title', 'Тэг: ' . $tag->name)

@section('app_content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Поиск по тэгу: {{$tag->name}}
        </h3>

        @foreach($publicModels as $model => $options)
            @if($tag[$options['relation']]->isNotEmpty())
                <h4>{{$options['title']}}</h4>
                <ul class="list-group">
                    @foreach($tag[$options['relation']] as $item)
                        <li class="list-group-item" ><a href="{{ route($options['showView'], [$options['item'] => $item]) }}">{{$item->title}}</a></li>
                    @endforeach
                </ul>
                <hr>
            @endif
        @endforeach
    </div>
@endsection
