@extends('layout.app')
@section('title', 'Page Demo Title')

@section('app_content')
    Содержимое страницы Demo
@endsection

@section('sidebar')
    @parent
    Переопределённое содержимое боковой панели
    <script>
        var app = @json($data)
    </script>
@endsection
