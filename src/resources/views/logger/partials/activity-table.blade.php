@php

$drilldownStatus = config('LaravelVisitorLogger.enableDrillDown');
$prependUrl = '/activity/log/';

if (isset($hoverable) && $hoverable === true) {
    $hoverable = true;
} else {
    $hoverable = false;
}

if (Request::is('activity/cleared')) {
    $prependUrl = '/activity/cleared/log/';
}

@endphp

<div class="table-responsive activity-table">
    <table class="table table-striped table-condensed table-sm @if(config('LaravelVisitorLogger.enableDrillDown') && $hoverable) table-hover @endif data-table">
        <thead>
            <tr>
                <th>
                    <i class="fa fa-database fa-fw" aria-hidden="true"></i>
                    <span class="hidden-sm hidden-xs">
                        {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.labels.id') !!}
                    </span>
                </th>
                <th>
                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                    {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.labels.time') !!}
                </th>
                <th>
                    <i class="fa fa-file-text-o fa-fw" aria-hidden="true"></i>
                    {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.labels.description') !!}
                </th>
                <th>
                    <i class="fa fa-user-o fa-fw" aria-hidden="true"></i>
                    {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.labels.user') !!}
                </th>
                <th>
                    <i class="fa fa-truck fa-fw" aria-hidden="true"></i>
                    <span class="hidden-sm hidden-xs">
                        {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.labels.method') !!}
                    </span>
                </th>
                <th>
                    <i class="fa fa-map-o fa-fw" aria-hidden="true"></i>
                    {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.labels.route') !!}
                </th>
                <th>
                    <i class="fa fa-map-marker fa-fw" aria-hidden="true"></i>
                    {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.labels.ipAddress') !!}
                </th>
                <th>
                    <i class="fa fa-laptop fa-fw" aria-hidden="true"></i>
                    {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.labels.agent') !!}
                </th>
                @if(Request::is('activity/cleared'))
                    <th>
                        <i class="fa fa-trash-o fa-fw" aria-hidden="true"></i>
                        {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.labels.deleteDate') !!}
                    </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($activities as $visitorActivity)
                <tr @if($drilldownStatus && $hoverable) class="clickable-row" data-href="{{$prependUrl . $visitorActivity->id}}" data-toggle="tooltip" title="{{trans('LaravelVisitorLogger::laravel-logger.tooltips.viewRecord')}}" @endif >
                    <td>
                        <small>
                            {{ $visitorActivity->id }}
                        </small>
                    </td>
                    <td>
                        {{ $visitorActivity->timePassed }}
                    </td>
                    <td>
                        {{ $visitorActivity->description }}
                    </td>
                    <td>
                        @php
                            switch ($visitorActivity->userType) {
                                case trans('LaravelVisitorLogger::laravel-logger.userTypes.registered'):
                                    $userTypeClass = 'success';
                                    $userLabel = $visitorActivity->userDetails['name'];
                                    break;

                                case trans('LaravelVisitorLogger::laravel-logger.userTypes.crawler'):
                                    $userTypeClass = 'danger';
                                    $userLabel = $visitorActivity->userType;
                                    break;

                                case trans('LaravelVisitorLogger::laravel-logger.userTypes.guest'):
                                default:
                                    $userTypeClass = 'warning';
                                    $userLabel = $visitorActivity->userType;
                                    break;
                            }

                        @endphp
                        <span class="badge badge-{{$userTypeClass}}">
                            {{$userLabel}}
                        </span>
                    </td>
                    <td>
                        @php
                            switch (strtolower($visitorActivity->methodType)) {
                                case 'get':
                                    $methodClass = 'info';
                                    break;

                                case 'post':
                                    $methodClass = 'warning';
                                    break;

                                case 'put':
                                    $methodClass = 'warning';
                                    break;

                                case 'delete':
                                    $methodClass = 'danger';
                                    break;

                                default:
                                    $methodClass = 'info';
                                    break;
                            }
                        @endphp
                        <span class="badge badge-{{ $methodClass }}">
                            {{ $visitorActivity->methodType }}
                        </span>
                    </td>
                    <td class="ellipsis">
                        @if($hoverable)
                            {{ showCleanRoutUrl($visitorActivity->route) }}
                        @else
                            <a href="{{ $visitorActivity->route }}">
                                {{$visitorActivity->route}}
                            </a>
                        @endif
                    </td>
                    <td>
                        {{ $visitorActivity->ipAddress }}
                    </td>
                    <td>
                        @php
                            $platform       = $visitorActivity->userAgentDetails['platform'];
                            $browser        = $visitorActivity->userAgentDetails['browser'];
                            $browserVersion = $visitorActivity->userAgentDetails['version'];

                            switch ($platform) {

                                case 'Windows':
                                    $platformIcon = 'fa-windows';
                                    break;

                                case 'iPad':
                                    $platformIcon = 'fa-';
                                    break;

                                case 'iPhone':
                                    $platformIcon = 'fa-';
                                    break;

                                case 'Macintosh':
                                    $platformIcon = 'fa-apple';
                                    break;

                                case 'Android':
                                    $platformIcon = 'fa-android';
                                    break;

                                case 'BlackBerry':
                                    $platformIcon = 'fa-';
                                    break;

                                case 'Unix':
                                case 'Linux':
                                    $platformIcon = 'fa-linux';
                                    break;

                                default:
                                    $platformIcon = 'fa-';
                                    break;
                            }

                            switch ($browser) {

                                case 'Chrome':
                                    $browserIcon  = 'fa-chrome';
                                    break;

                                case 'Firefox':
                                    $browserIcon  = 'fa-';
                                    break;

                                case 'Opera':
                                    $browserIcon  = 'fa-opera';
                                    break;

                                case 'Safari':
                                    $browserIcon  = 'fa-safari';
                                    break;

                                case 'Internet Explorer':
                                    $browserIcon  = 'fa-edge';
                                    break;

                                default:
                                    $browserIcon  = 'fa-';
                                    break;
                            }
                        @endphp
                        <i class="fa {{ $browserIcon }} fa-fw" aria-hidden="true">
                            <span class="sr-only">
                                {{ $browser }}
                            </span>
                        </i>
                        <sup>
                            <small>
                                {{ $browserVersion }}
                            </small>
                        </sup>
                        <i class="fa {{ $platformIcon }} fa-fw" aria-hidden="true">
                            <span class="sr-only">
                                {{ $platform }}
                            </span>
                        </i>
                        <sup>
                            <small>
                                {{ $visitorActivity->langDetails }}
                            </small>
                        </sup>
                    </td>
                    @if(Request::is('activity/cleared'))
                        <td>
                            {{ $visitorActivity->deleted_at }}
                        </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if(config('LaravelVisitorLogger.loggerPaginationEnabled'))
    <div class="text-center">
        <div>
            {!! $activities->render() !!}
        </div>
        <p>
            {!! trans('LaravelVisitorLogger::laravel-logger.pagination.countText', ['firstItem' => $activities->firstItem(), 'lastItem' => $activities->lastItem(), 'total' => $activities->total(), 'perPage' => $activities->perPage()]) !!}
        </p>
    </div>
@endif
