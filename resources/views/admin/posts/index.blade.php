@extends('layout.master')

@section('title', 'Администрирование статей')

@section('content')
    <div class="col-md-8 p-0">
        <table class="table">
            <thead class="thead-dark table-bordered ">
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
        <div class="col pl-15">
            <nav class="blog-pagination">
                <a class="btn btn-outline-primary" href="#">Старше</a>
                <a class="btn btn-outline-secondary disabled" href="#" tabindex="-1" aria-disabled="true">Новее</a>
            </nav>
        </div>
    </div>
@endsection
