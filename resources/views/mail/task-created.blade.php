@component('mail::message')
Создана новая задача: {{ $task->title }}

{{ $task->body }}

@component('mail::button', ['url' => '/tasks/' . $task->id])
Смотреть задачу
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
