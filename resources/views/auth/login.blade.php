@extends('layouts.single-box')

@section('page-title', trans('login.sign.in') )

@section('content')
    <div class="login-box-body">
        <p class="login-box-msg">@lang('login.title')</p>

        <form action="{{ url('/login') }}" role="form" method="post">
            {!! csrf_field() !!}

            @set( $loginBy , setting('login.using'))

            <div class="form-group has-feedback{{ $errors->has($loginBy) ? ' has-error' : '' }}">
                @if($loginBy == 'email')
                    <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                @else
                    <input name="username" type="text" class="form-control" placeholder="Username" value="{{ old('username') }}">
                @endif
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @if ($errors->has($loginBy))
                    <span class="help-block">
                        <strong>{{ $errors->first($loginBy) }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input name="password" type="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label>
                            <input name="remember" type="checkbox"> @lang('login.remember')
                        </label>
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">@lang('login.sign.in')</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <a href="{{ url('/password/reset') }}">@lang('login.forgot.password')</a><br>
    </div>
@endsection