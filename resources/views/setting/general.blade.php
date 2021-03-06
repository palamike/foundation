@extends('layouts.master')

@section('page-title',trans('setting.group.general'))

@section('page-header', trans('setting.group.general'))

@section('page-description', trans('setting.group.general.description'))

@section('content')
    <div id="app-content">
        <setting
                setting-name="general"
                edit-action="{{route('setting.edit',['name' => 'general'])}}"
                update-action="{{route('setting.update',['name' => 'general'])}}"
                token="{{ csrf_token() }}"
                :permissions="permissions"
        ></setting>
    </div>
@endsection

@section('page-scripts')

    <script type="text/javascript">
        var lang = '{{app_locale()}}';
        var locales = JSON.parse('{!! get_lang_array('setting','setting.php') !!}');
        var permissions = JSON.parse('{!! json_encode($permissions) !!}');
    </script>

    {!! scripts(['app.setting']) !!}
@endsection