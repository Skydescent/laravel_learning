@extends('layout.admin')

@section('title', 'Отчёты')

@section('admin_content')
    <div class="col-md-8 blog-main">
        <h3 class="pb-4 mb-4 font-italic border-bottom">
            Отчёты
        </h3>

       <ul>
           @foreach($reports as $name => $report)
               <li><a href="{{route('admin.reports.make',['report' => $name])}}">{{$report['title']}}</a></li>
           @endforeach
       </ul>

    </div>
@endsection
