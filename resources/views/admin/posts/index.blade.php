@extends('layout.master')

@section('title', 'Администрирование статей')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Список статей
        </h3>
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Slug</th>
                <th scope="col">Заголовок</th>
                <th scope="col">Кратко о статье</th>
                <th scope="col">Опубликовано</th>
                <th scope="col">Автор</th>
                <th scope="col">Действия</th>

            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                @include('admin.posts.item')
            @endforeach
            </tbody>
        </table>

        <nav class="blog-pagination">
            <a class="btn btn-outline-primary" href="#">Старше</a>
            <a class="btn btn-outline-secondary disabled" href="#" tabindex="-1" aria-disabled="true">Новее</a>
        </nav>

    </div>
@endsection
