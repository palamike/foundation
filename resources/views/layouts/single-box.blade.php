@extends('layouts.app')

@section('body-class','login-page')

@section('template')
    <div class="login-box">
        <div class="login-logo">
            <a href="#">{{setting('application.name')}}</a>
        </div>
        <!-- /.login-logo -->

        @yield('content')
    </div>
    <!-- /.login-box -->
@endsection

@section('page-scripts')
    <script>
        $(function () {
            UserInterfaceUtil.icheck();
        });
    </script>
@endsection