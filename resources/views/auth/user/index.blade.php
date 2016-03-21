@extends('layouts.master')

@section('page-title',trans('user.user.manage'))

@section('page-header', trans('user.user.manage'))

@section('page-description', trans('user.user.manage.description'))

@section('content')
    <div id="app-content">
        <user-mgmt
                media-upload-url="{{route('media.upload')}}"
                media-delete-url="{{route('media.delete', ['media' => 'replacement'])}}"
                web-url="{{url('/')}}"
                list-action="{{route('auth.user.list')}}"
                create-action="{{route('auth.user.create')}}"
                store-action="{{route('auth.user.store')}}"
                edit-action="{{route('auth.user.edit',['user' => 'replacement'])}}"
                update-action="{{route('auth.user.update',['user' => 'replacement'])}}"
                destroy-action="{{route('auth.user.destroy',['user' => 'replacement'])}}"
                token="{{ csrf_token() }}"
                :permissions="permissions"
        ></user-mgmt>
    </div>
@endsection

@section('page-scripts')

    <script type="text/javascript">
        var lang = '{{app_locale()}}';
        var locales = JSON.parse('{!! get_lang_array('user','user.php') !!}');
        var permissions = JSON.parse('{!! json_encode($permissions) !!}');
    </script>

    {!! scripts(['app.auth.user']) !!}
@endsection