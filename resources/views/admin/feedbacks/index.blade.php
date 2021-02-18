@extends('admin.layout.table')

@section('title', 'Администрирование обращений')

@section('table_header')
    <th scope="col">Email</th>
    <th scope="col">Сообщение</th>
    <th scope="col">Дата</th>
@endsection
@section('table_body')
    @foreach($feedbacks as $feedback)
        @include('admin.feedbacks.item')
    @endforeach
@endsection
