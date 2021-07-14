@php
    $extendedView = 'layout.app';
    $content = 'app_content';
    $methodPrefix = '';
    if ($isAdmin) {
        $extendedView = 'layout.admin';
        $content = 'admin_content';
        $methodPrefix = 'admin.';
    }
@endphp
@extends("{$extendedView}")

@section('title', 'Изменение статьи')

@section("{$content}")
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Изменение статьи
        </h3>

        @include('layout.errors')

        <form method="post" action="{{ route($methodPrefix . 'posts.update', ['post' => $post]) }}">

            @csrf
            @method('PATCH')

            @include('posts.createOrUpdate', [
                'btnText' => 'Изменить',
            ])
        </form>

        <form method="POST" action="{{route($methodPrefix . 'posts.destroy', ['post' => $post])}}" class="mt-4">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Удалить</button>
        </form>
    </div>
@endsection
