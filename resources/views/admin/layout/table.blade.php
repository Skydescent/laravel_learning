@extends('layout.admin')

@section('admin_content')
    <div class="col-md-8 p-0">
        <table class="table table-bordered">
            <div class="fixed-top">
                <thead class="thead-dark">
                <tr class="admin-tb-header">
                    @yield('table_header')
                </tr>
                </thead>
            </div>

            <tbody>
                @yield('table_body')
            </tbody>
        </table>
    </div>
@endsection