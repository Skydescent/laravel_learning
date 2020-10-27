@extends('layout.master');

@section('content');
<div class="col-md-8 blog-main">
    <h3 class="pb-4 mb-4 font-italic border-bottom">
        {{ $task->title }}
    </h3>

    {{ $task->body }}

    <hr>
    <a href="/tasks">Вернуться к списку</a>
</div>
@endsection
