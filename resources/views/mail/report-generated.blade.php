@component('mail::message')
    <h3>Отчёт сгенерирован</h3>
    <ul>
        @foreach($reportFields as $field)
            <li>{{$field['title'] . " : " . $field['value']}}</li>
        @endforeach
    </ul>

@endcomponent