@extends('layout.master')

@section('content')
    @include('layout.nav')
    @include('layout.flash_message')
    <main role="main" class="container" id="app">
        <div class="row">
            @yield('app_content')
            @section('sidebar')
                @include('layout.sidebar')
            @show
        </div>
        <post-update></post-update>
    </main>
    @include('layout.footer')
@endsection
