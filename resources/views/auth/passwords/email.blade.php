@extends('layouts.single-box')

@section('page-title', trans('login.reset.password') )

@section('content')

    @if (session('status'))
        <div class="callout callout-success">
            <h4>{{ session('status') }}</h4>
        </div>
    @endif

    <div class="login-box-body">
        <p class="login-box-msg">@lang('login.reset.password')</p>

        <form action="{{ url('/password/email') }}" role="form" method="post">
            {!! csrf_field() !!}
            @lang('login.email.address')
            <div class="form-group has-feedback{{ $errors->has('email') ? ' has-error' : '' }}">
                <input name="email" type="email" class="form-control" placeholder="Email" value="{{ old('email') }}">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
                @if ($errors->has('email'))
                    <span class="help-block">
                       <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">@lang('login.send.password')</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
    </div>
@endsection