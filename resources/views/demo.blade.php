@extends('app')
@section('title', 'Page Demo Title')

@section('content')
    Содержимое страницы Demo
@endsection

@section('sidebar')
    @parent
    Переопределённое содержимое боковой панели
    <script>
        var app = @json($data)
    </script>
@endsection
