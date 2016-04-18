@extends('foundation::layouts.app')

@section('body-class','skin-blue sidebar-mini')

@section('template')
<!-- Main Header -->
@include('foundation::layouts.partials.header')
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->

        <!-- search form (Optional) -->

        <!-- /.search form -->

        <!-- Sidebar Menu -->
        @include('foundation::layouts.partials.menu')
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            @yield('page-header')
            <small>@yield('page-description')</small>
        </h1>

        @yield('breadcrumb')

    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Your Page Content Here -->
        @yield('content')

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Main Footer -->
@include('foundation::layouts.partials.footer')

<!-- Control Sidebar -->
<!-- /.control-sidebar -->
<!-- Add the sidebar's background. This div must be placed
     immediately after the control sidebar -->
<div class="control-sidebar-bg"></div>
@endsection