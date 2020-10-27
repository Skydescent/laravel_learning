@extends('layout.master')

@section('title', 'Контакты')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Наши контакты:
        </h3>
        <p>Наш телефон: 223-33-22-134</p>
        <p>Электронная почта: superBlog@mail.com</p>

        @include('layout.errors')

        <h4 class="pb-4 mb-4 border-bottom">
            Форма обратной связи:
        </h4>
        <form method="post" action="{{route('feedbacks.store')}}">

            @csrf

            <div class="form-group">
                <label for="inputEmail">Email</label>
                <input type="email" class="form-control" id="inputEmail" name= "email" value="{{old('email')}}" placeholder="ваш Email">
            </div>
            <div class="form-group">
                <label for="inputBody">Текст обращения</label>
                <textarea name="body"  id="inputBody" cols="30" rows="5" class="form-control" >{{old('body')}}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </form>
    </div>
@endsection
