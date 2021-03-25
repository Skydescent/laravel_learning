@extends('layout.admin')

@section('title', $report['title'])

@section('admin_content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            {{$report['title']}}
        </h3>

        @include('layout.errors')

        <form method="post" action="{{ route('admin.reports.send', ['report' => $reportAlias]) }}">

            @csrf
            @method('GET')

            @foreach($report['reportable'] as $fieldAlias => $reportField)
                <div class="form-group form-check">
                    <input type="checkbox"
                           value="1"
                           class="form-check-input"
                           id="is{{ucfirst($fieldAlias)}}"
                           name="{{$fieldAlias}}">
                    <label class="form-check-label" for="is{{ucfirst($fieldAlias)}}">
                        {{$reportField['title']}}
                    </label>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary">Сгенерировать отчёт</button>
        </form>
    </div>
@endsection