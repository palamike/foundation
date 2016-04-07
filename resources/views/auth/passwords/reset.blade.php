@extends('foundation::layouts.single-box')

@section('page-title', trans('foundation::login.reset.password') )

@section('content')
    <div class="login-box-body">
        <p class="login-box-msg">@lang('foundation::login.reset.password')</p>

        <form action="{{ url('/password/reset') }}" role="form" method="post">
            {!! csrf_field() !!}

            <input type="hidden" name="token" value="{{ $token }}">

            @lang('foundation::login.email.address')
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <input name="email" type="email" class="form-control" placeholder="Email Address" value="{{ $email or old('email') }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>

            @lang('foundation::login.password')
            <div class="form-group has-feedback{{ $errors->has('password') ? ' has-error' : '' }}">
                <input name="password" type="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>

            @lang('foundation::login.confirm.password')
            <div class="form-group has-feedback{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <input name="password_confirmation" type="password" class="form-control" placeholder="Password Confirmation">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>


            <div class="row">
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">@lang('foundation::login.reset.password')</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
@endsection