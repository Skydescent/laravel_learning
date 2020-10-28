@extends('layout.master')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Создание задачи
        </h3>

        @include('layout.errors')

        <form method="post" action="{{route('tasks.store')}}">

            @csrf

            <div class="form-group">
                <label for="inputTitle">Название задачи</label>
                <input type="text" class="form-control" id="inputTitle" name= "title" placeholder="Введите название задачи">

            </div>
            <div class="form-group">
                <label for="inputBody">Описание задачи</label>
                <input type="text" class="form-control" id="inputBody" name="body" placeholder="Введите описание задачи">
            </div>

            <button type="submit" class="btn btn-primary">Создать задачу</button>
        </form>
    </div>
@endsection
