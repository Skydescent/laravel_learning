@extends('admin.layout.table')

@section('title', 'Администрирование новостей')

@section('table_header')
    <tr class="admin-tb-header">
        <th scope="col">#</th>
        <th scope="col">Заголовок</th>
        <th scope="col">Текст</th>
        <th scope="col">Опубликовано</th>
        <th scope="col">Действия</th>
    </tr>
    <tr class="admin-tb-header">
        <td colspan="5">
            <a href="{{ route('admin.news.create') }}" class="btn btn-sm btn-success btn-block">ДОБАВИТЬ НОВОСТЬ</a>
        </td>
    </tr>
@endsection
@section('table_body')
    @foreach($news as $pieceOfNews)
        @include('admin.news.item')
    @endforeach
@endsection

@section('table_pagination')
    {{$news->links()}}
@endsection
