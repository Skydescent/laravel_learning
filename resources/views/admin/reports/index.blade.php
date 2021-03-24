@extends('layout.admin')

@section('title', 'Отчёты')

@section('app_content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Отчёты
        </h3>

       <ul>
           <li>Общее количество статей: {{$statistics['postsCount']}}</li>
       </ul>

    </div>
@endsection
