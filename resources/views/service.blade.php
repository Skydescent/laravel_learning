@extends('layout.master')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Отправить уведомление
        </h3>

        @include('layout.errors')

        <form method="post" action="{{route('tasks.store')}}">

            @csrf

            <div class="form-group">
                <label for="inputTitle">Название задачи</label>
                <input type="text" class="form-control" id="inputTitle"  placeholder="Введите название задачи"
                       name= "title"
                       value="{{ old('title')}}">
            </div>
            <div class="form-group">
                <label for="inputBody">Описание задачи</label>
                <textarea name="body"  class="form-control" id="inputBody">{{ old('body', 'Введите описание')}}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Создать задачу</button>
        </form>
    </div>
@endsection
