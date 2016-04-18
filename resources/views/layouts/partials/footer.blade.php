<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        {{ setting('application.footer.info') }}
    </div>
    <!-- Default to the left -->
    <strong>@lang('foundation::common.copy.right') &copy; {{date('Y')}} <a href="{{ setting('application.footer.license.link') }}">{{ setting('application.footer.license.to') }}</a>.</strong> @lang('foundation::common.all.right.reserve')
</footer>