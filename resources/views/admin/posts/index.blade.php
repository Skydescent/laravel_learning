@extends('admin.layout.table')

@section('title', 'Администрирование статей')

@section('table_header')
    <th scope="col">#</th>
    <th scope="col">Заголовок</th>
    <th scope="col">Кратко о статье</th>
    <th scope="col">Опубликовано</th>
    <th scope="col">Автор</th>
    <th scope="col">Действия</th>
@endsection
@section('table_body')
    @foreach($posts as $post)
        @include('admin.posts.item')
    @endforeach
@endsection
@section('table_pagination')
    {{$posts->links()}}
@endsection