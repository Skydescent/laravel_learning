@extends('layout.master')

@section('title', 'Главная')

@section('content')

    <div class="row mb-2">
        @foreach($posts as $post)
            @include('posts.item')
        @endforeach
    </div>
    <nav class="blog-pagination">
        <a class="btn btn-outline-primary" href="#">Старше</a>
        <a class="btn btn-outline-secondary disabled" href="#" tabindex="-1" aria-disabled="true">Новее</a>
    </nav>

</div>
@endsection
