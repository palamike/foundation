@extends('foundation::layouts.master')

@section('page-title',trans('foundation::test.vue.element.title'))

@section('page-header', trans('foundation::test.vue.element.title'))

@section('page-description', trans('foundation::test.vue.element.description'))

@section('content')
<div id="application">
    <test-general></test-general>
</div>
@endsection

@section('page-scripts')

    <script type="text/javascript">
        var options = {
            url : '{{ route('test.vue.app') }}',
            debug : true
        };
    </script>

    {!! scripts(['test.vue.element']) !!}
@endsection