@extends('layout.master')
@section('content')
    @include('layout.flash_message')
    <main id="app">
        <div class="row">
            @include('admin.layout.nav')
            @yield('admin_content')
        </div>
    </main>
@endsection
