@extends('layout.master')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Отправить уведомление
        </h3>

        @include('layout.errors')

        <form method="post" action="/service">

            @csrf

            <div class="form-group">
                <label for="inputTitle">Заголовок уведомления</label>
                <input type="text" class="form-control" id="inputTitle"  placeholder="Введите заголовок уведомления"
                       name= "title"
                       value="{{ old('title')}}">
            </div>
            <div class="form-group">
                <label for="inputText">Текст уведомления</label>
                <textarea name="text"  class="form-control" id="inputText">{{ old('text')}}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>
@endsection
