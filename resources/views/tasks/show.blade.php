@extends('layout.master')

@section('content')
<div class="col-md-8 blog-main">
    <h3 class="pb-4 mb-4 font-italic border-bottom">
        {{ $task->title }}
        <a href="{{route('tasks.edit', ['task' => $task])}}">Изменить</a>
    </h3>

    {{ $task->body }}

    @if($task->steps->isNotEmpty())
        <ul class="lsit-group">
            @foreach($task->steps as $step)
                <li class="list-group-item">
                    <form method="POST" action="/steps/{{ $step->id }}">
                        @method('PATCH')
                        @csrf
                        <label class="form-check-label">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="completed"
                                onclick="this.form.submit()"
                            >
                            {{$step->description}}
                        </label>
                    </form>
                </li>

            @endforeach
        </ul>
    @endif

    <hr>
    <a href="{{route('tasks.index')}}">Вернуться к списку</a>

</div>
@endsection
