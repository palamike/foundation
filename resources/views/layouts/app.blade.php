<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('page-title') | {{ setting('application.name') }}</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <link rel="icon"
          type="image/png"
          href="{{url('assets/images/favicon.png')}}" />

    <title>@yield('page-title') | {{setting('application.name')}}</title>

    {!! styles([
        "font.th_k2d_july8",
        "vendor.bootstrap" ,
        "vendor.font-awesome",
        "vendor.ionicons",
        "vendor.animate",
        "vendor.admin-lte",
        "vendor.admin-lte.skin-blue",
        "vendor.icheck.blue",
        "vendor.foundation"
        ]) !!}

    @yield('template-styles')

    @yield('page-styles')

    {{-- styles("app.main") --}}

    {!! scripts(["vendor.respond", "vendor.fastclick"]) !!}

</head>
<body class="hold-transition @yield('body-class')">

    @yield('template')

    <!-- JavaScripts -->
    {!! scripts([
        "vendor.jquery",
        "vendor.bootstrap",
        "vendor.icheck",
        "vendor.admin-lte",
        "vendor.foundation"
        ]) !!}

    @yield('template-scripts')

    {!! scripts(["app.main"]) !!}

    @yield('page-scripts')
</body>
</html>
