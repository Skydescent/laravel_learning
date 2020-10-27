@extends('layout.master')

@section('title', 'Админ раздел: обращения')

@section('content')

    <table class="table table-bordered">
        <thead>
        <tr>
            <th scope="col">Email</th>
            <th scope="col">Сообщение</th>
            <th scope="col">Дата</th>
        </tr>
        </thead>
        <tbody>
        @foreach($feedbacks as $feedback)
            @include('feedbacks.item')
        @endforeach
        </tbody>
    </table>
    <nav class="blog-pagination">
        <a class="btn btn-outline-primary" href="#">Старше</a>
        <a class="btn btn-outline-secondary disabled" href="#" tabindex="-1" aria-disabled="true">Новее</a>
    </nav>

@endsection
