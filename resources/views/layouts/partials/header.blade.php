<header class="main-header">
    <!-- Logo -->
    <a href="{{ url("/")  }}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">{!! accronyme() !!}</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">{!! app_name_decorated()  !!}</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="{{ avatar()  }}" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{ user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <img src="{{ avatar()  }}" class="img-circle" alt="User Image">
                            <p>
                                {{ user()->name }}
                                <small>{{ role()->label }}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!-- End Menu Body -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            @can('user_profile_view')
                            <div class="pull-left">
                                <a href="{{ route('auth.profile.index') }}" class="btn btn-default btn-flat">@lang('user.profile')</a>
                            </div>
                            @endcan
                            <div class="pull-right">
                                <a href="{{url('logout')}}" class="btn btn-default btn-flat">@lang('login.sign.out')</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
            </ul>
        </div>
    </nav>
</header>