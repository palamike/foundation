@extends('layouts.master')

@section('page-title',trans('user.profile'))

@section('page-header', trans('user.profile'))

@section('page-description', trans('user.profile.description'))

@section('content')
    <div id="app-content">
        <user-profile
                media-upload-url="{{route('media.upload')}}"
                media-delete-url="{{route('media.delete', ['media' => 'replacement'])}}"
                web-url="{{url('/')}}"
                edit-action="{{route('auth.profile.edit',['profile' => 'profile'])}}"
                update-action="{{route('auth.profile.update',['profile' => 'profile'])}}"
                token="{{ csrf_token() }}"
                :permissions="permissions"
        ></user-profile>
    </div>
@endsection

@section('page-scripts')

    <script type="text/javascript">
        var lang = '{{app_locale()}}';
        var locales = JSON.parse('{!! get_lang_array('user','user.php') !!}');
        var permissions = JSON.parse('{!! json_encode($permissions) !!}');
    </script>

    {!! scripts(['app.auth.profile']) !!}
@endsection