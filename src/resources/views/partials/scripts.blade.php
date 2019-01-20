
@if(config('LaravelVisitorLogger.enablejQueryCDN'))
    <script type="text/javascript" src="{{ config('LaravelVisitorLogger.JQueryCDN') }}"></script>
@endif

@if(config('LaravelVisitorLogger.enableBootstrapJsCDN'))
    <script type="text/javascript" src="{{ config('LaravelVisitorLogger.bootstrapJsCDN') }}"></script>
@endif

@if(config('LaravelVisitorLogger.enablePopperJsCDN'))
    <script type="text/javascript" src="{{ config('LaravelVisitorLogger.popperJsCDN') }}"></script>
@endif

@if(config('LaravelVisitorLogger.loggerDatatables'))
    @if (count($activities) > 10)
        @include('LaravelVisitorLogger::scripts.datatables')
    @endif
@endif