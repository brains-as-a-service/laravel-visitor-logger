@extends(config('LaravelVisitorLogger.loggerBladeExtended'))

@if(config('LaravelVisitorLogger.bladePlacement') == 'yield')
    @section(config('LaravelVisitorLogger.bladePlacementCss'))
@elseif (config('LaravelVisitorLogger.bladePlacement') == 'stack')
    @push(config('LaravelVisitorLogger.bladePlacementCss'))
@endif

        @include('LaravelVisitorLogger::partials.styles')

@if(config('LaravelVisitorLogger.bladePlacement') == 'yield')
    @endsection
@elseif (config('LaravelVisitorLogger.bladePlacement') == 'stack')
    @endpush
@endif

@if(config('LaravelVisitorLogger.bladePlacement') == 'yield')
    @section(config('LaravelVisitorLogger.bladePlacementJs'))
@elseif (config('LaravelVisitorLogger.bladePlacement') == 'stack')
    @push(config('LaravelVisitorLogger.bladePlacementJs'))
@endif

        @include('LaravelVisitorLogger::partials.scripts', ['activities' => $activities])
        @include('LaravelVisitorLogger::scripts.confirm-modal', ['formTrigger' => '#confirmDelete'])
        @include('LaravelVisitorLogger::scripts.confirm-modal', ['formTrigger' => '#confirmRestore'])

        @if(config('LaravelVisitorLogger.enableDrillDown'))
            @include('LaravelVisitorLogger::scripts.clickable-row')
            @include('LaravelVisitorLogger::scripts.tooltip')
        @endif

@if(config('LaravelVisitorLogger.bladePlacement') == 'yield')
    @endsection
@elseif (config('LaravelVisitorLogger.bladePlacement') == 'stack')
    @endpush
@endif


@section('template_title')
    {{ trans('LaravelVisitorLogger::laravel-logger.dashboardCleared.title') }}
@endsection

@php
    switch (config('LaravelVisitorLogger.bootstapVersion')) {
        case '4':
            $containerClass = 'card';
            $containerHeaderClass = 'card-header';
            $containerBodyClass = 'card-body';
            break;
        case '3':
        default:
            $containerClass = 'panel panel-default';
            $containerHeaderClass = 'panel-heading';
            $containerBodyClass = 'panel-body';
    }
    $bootstrapCardClasses = (is_null(config('LaravelVisitorLogger.bootstrapCardClasses')) ? '' : config('LaravelVisitorLogger.bootstrapCardClasses'));
@endphp

@section('content')

    <div class="container-fluid">

        @if(config('LaravelVisitorLogger.enablePackageFlashMessageBlade'))
            @include('LaravelVisitorLogger::partials.form-status')
        @endif

        <div class="row">
            <div class="col-sm-12">
                <div class="{{ $containerClass }} {{ $bootstrapCardClasses }}">
                    <div class="{{ $containerHeaderClass }}">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span>
                                {!! trans('LaravelVisitorLogger::laravel-logger.dashboardCleared.title') !!}
                                <sup class="label">
                                    {{ $totalActivities }} {!! trans('LaravelVisitorLogger::laravel-logger.dashboardCleared.subtitle') !!}
                                </sup>
                            </span>
                            <div class="btn-group pull-right btn-group-xs">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-ellipsis-v fa-fw" aria-hidden="true"></i>
                                    <span class="sr-only">
                                        {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.menu.alt') !!}
                                    </span>
                                </button>
                                @if(config('LaravelVisitorLogger.bootstapVersion') == '4')
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a href="{{route('activity')}}" class="dropdown-item">
                                            <span class="text-primary">
                                                <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                                                {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.menu.back') !!}
                                            </span>
                                        </a>
                                        @if($totalActivities)
                                            @include('LaravelVisitorLogger::forms.delete-activity-log')
                                            @include('LaravelVisitorLogger::forms.restore-activity-log')
                                        @endif
                                    </div>
                                @else
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{route('activity')}}">
                                                <span class="text-primary">
                                                    <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                                                    {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.menu.back') !!}
                                                </span>
                                            </a>
                                        </li>
                                        @if($totalActivities)
                                            <li>
                                                @include('LaravelVisitorLogger::forms.delete-activity-log')
                                            </li>
                                            <li>
                                                @include('LaravelVisitorLogger::forms.restore-activity-log')
                                            </li>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        @include('LaravelVisitorLogger::logger.partials.activity-table', ['activities' => $activities, 'hoverable' => true])
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('LaravelVisitorLogger::modals.confirm-modal', ['formTrigger' => 'confirmDelete', 'modalClass' => 'danger', 'actionBtnIcon' => 'fa-trash-o'])
    @include('LaravelVisitorLogger::modals.confirm-modal', ['formTrigger' => 'confirmRestore', 'modalClass' => 'success', 'actionBtnIcon' => 'fa-check'])

@endsection
