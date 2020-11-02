@extends('layout.master')

@section('title', 'Создание статьи')

@section('content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Создание статьи
        </h3>

        @include('layout.errors')

        <form method="post" action="{{route('posts.store')}}">

            @csrf

            <div class="form-group">
                <label for="slug">Название статьи</label>
                <input type="text" class="form-control" id="slug" name= "slug" value="{{old('slug')}}" placeholder="Slug статьи">

            </div>
            <div class="form-group">
                <label for="inputTitle">Название статьи</label>
                <input type="text" class="form-control" id="inputTitle" name= "title" value="{{old('title')}}" placeholder="Введите заголовок статьи">

            </div>

            <div class="form-group">
                <label for="shortText">Краткое описание статьи</label>
                <textarea name="short_text" id="shortText" cols="30" rows="2" class="form-control" >{{old('short_text')}}</textarea>
            </div>
            <div class="form-group">
                <label for="body">Детальное описание статьи</label>
                <textarea name="body" cols="30" rows="7" class="form-control">{{old('body')}}</textarea>
            </div>
            <div class="form-group form-check">
                <input type="checkbox" value="1" name="published" class="form-check-input" id="isPublished" {{ old('published') == '1' ? 'checked' : ''}}>
                <label class="form-check-label" for="isPublished">Опубликовать</label>
            </div>

            <button type="submit" class="btn btn-primary">Создать</button>
        </form>
    </div>
@endsection
