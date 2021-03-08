@extends('layout.admin')

@section('title', 'Создание новости')

@section('admin_content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Создание новости
        </h3>

        @include('layout.errors')

        <form method="post" action="{{ route('admin.news.store') }}">

            @csrf

            @include('admin.news.createOrUpdate', [
                'btnText' => 'Создать',
            ])

        </form>
    </div>
@endsection
