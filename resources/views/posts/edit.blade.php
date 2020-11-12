@extends('layout.master')

@section('title', 'Изменение статьи')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Изменение статьи
        </h3>

        @include('layout.errors')

        <form method="post" action="{{ route('posts.update', ['post' => $post]) }}">

            @csrf
            @method('PATCH')

            @include('posts.createOrUpdate', [
                'btnText' => 'Изменить',
            ])
        </form>

        <form method="POST" action="{{route('posts.destroy', ['post' => $post])}}" class="mt-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Удалить</button>
        </form>
    </div>
@endsection
