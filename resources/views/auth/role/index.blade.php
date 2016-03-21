@extends('layouts.master')

@section('page-title',trans('user.role.manage'))

@section('page-header', trans('user.role.manage'))

@section('page-description', trans('user.role.manage.description'))

@section('content')
    <div id="app-content">
        <role-mgmt
                list-action="{{route('auth.role.list')}}"
                create-action="{{route('auth.role.create')}}"
                store-action="{{route('auth.role.store')}}"
                edit-action="{{route('auth.role.edit',['role' => 'replacement'])}}"
                update-action="{{route('auth.role.update',['role' => 'replacement'])}}"
                destroy-action="{{route('auth.role.destroy',['role' => 'replacement'])}}"
                token="{{ csrf_token() }}"
                :permissions="permissions"
        ></role-mgmt>
    </div>
@endsection

@section('page-scripts')

    <script type="text/javascript">
        var lang = '{{app_locale()}}';
        var locales = JSON.parse('{!! get_lang_array('user','user.php') !!}');
        var permissions = JSON.parse('{!! json_encode($permissions) !!}');
    </script>

    {!! scripts(['app.auth.role']) !!}
@endsection