@extends('layout.admin')

@section('title', 'Изменение статьи')

@section('admin_content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Изменение статьи
        </h3>

        @include('layout.errors')

        <form method="post" action="{{ route('admin.news.update', ['news' => $news]) }}">

            @csrf
            @method('PATCH')

            @include('admin.news.createOrUpdate', [
                'btnText' => 'Изменить',
            ])
        </form>
    </div>
@endsection