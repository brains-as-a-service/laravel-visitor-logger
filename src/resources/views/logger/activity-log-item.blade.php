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

        @include('LaravelVisitorLogger::partials.scripts', ['activities' => $userActivities])

@if(config('LaravelVisitorLogger.bladePlacement') == 'yield')
    @endsection
@elseif (config('LaravelVisitorLogger.bladePlacement') == 'stack')
    @endpush
@endif

@section('template_title')
    {{ trans('LaravelVisitorLogger::laravel-logger.drilldown.title', ['id' => $visitorActivity->id]) }}
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

    switch ($visitorActivity->userType) {
        case trans('LaravelVisitorLogger::laravel-logger.userTypes.registered'):
            $userTypeClass = 'success';
            break;

        case trans('LaravelVisitorLogger::laravel-logger.userTypes.crawler'):
            $userTypeClass = 'danger';
            break;

        case trans('LaravelVisitorLogger::laravel-logger.userTypes.guest'):
        default:
            $userTypeClass = 'warning';
            break;
    }

    switch (strtolower($visitorActivity->methodType)) {
        case 'get':
            $methodClass = 'info';
            break;

        case 'post':
            $methodClass = 'primary';
            break;

        case 'put':
            $methodClass = 'caution';
            break;

        case 'delete':
            $methodClass = 'danger';
            break;

        default:
            $methodClass = 'info';
            break;
    }

    $platform       = $userAgentDetails['platform'];
    $browser        = $userAgentDetails['browser'];
    $browserVersion = $userAgentDetails['version'];

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

@section('content')
<div class="container-fluid">

    @if(config('LaravelVisitorLogger.enablePackageFlashMessageBlade'))
        @include('LaravelVisitorLogger::partials.form-status')
    @endif

    <div class="panel @if($isClearedEntry) panel-danger @else panel-default @endif">
        <div class="{{ $containerClass }} @if($isClearedEntry) panel-danger @else panel-default @endif">
        <div class="{{ $containerHeaderClass }} @if($isClearedEntry) bg-danger text-white @else @endif" >
            {!! trans('LaravelVisitorLogger::laravel-logger.drilldown.title', ['id' => $visitorActivity->id]) !!}
            <a href="@if($isClearedEntry) {{route('cleared')}} @else {{route('activity')}} @endif" class="btn @if($isClearedEntry) btn-default @else btn-info @endif btn-sm pull-right">
                <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
                {!! trans('LaravelVisitorLogger::laravel-logger.drilldown.buttons.back') !!}
            </a>
        </div>
        <div class="{{ $containerBodyClass }}">
            <div class="row">
                <div class="col-xs-12 col-12">
                    <div class="row">

                        <div class="col-md-6 col-lg-4">
                            <ul class="list-group">
                                <li class="list-group-item @if($isClearedEntry) list-group-item-danger @else active @endif">
                                    {!! trans('LaravelVisitorLogger::laravel-logger.drilldown.title-details') !!}
                                </li>
                                <li class="list-group-item">
                                    <dl class="dl-horizontal">
                                        <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.id') !!}</dt>
                                        <dd>{{$visitorActivity->id}}</dd>

                                        <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.description') !!}</dt>
                                        <dd>{{$visitorActivity->description}}</dd>

                                        <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.route') !!}</dt>
                                        <dd>
                                            <a href="@if($visitorActivity->route != '/')/@endif{{$visitorActivity->route}}">
                                                {{$visitorActivity->route}}
                                            </a>
                                        </dd>

                                        <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.agent') !!}</dt>
                                        <dd>
                                            <i class="fa {{ $platformIcon }} fa-fw" aria-hidden="true">
                                                <span class="sr-only">
                                                    {{ $platform }}
                                                </span>
                                            </i>
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
                                        </dd>

                                        <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.locale') !!}</dt>
                                        <dd>
                                            {{ $langDetails }}
                                        </dd>

                                        <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.referer') !!}</dt>
                                        <dd>
                                            <a href="{{ $visitorActivity->referer }}">
                                                {{ $visitorActivity->referer }}
                                            </a>
                                        </dd>

                                        <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.methodType') !!}</dt>
                                        <dd>
                                            <span class="badge badge-{{$methodClass}}">
                                                {{ $visitorActivity->methodType }}
                                            </span>
                                        </dd>

                                        <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.timePassed') !!}</dt>
                                        <dd>{{$timePassed}}</dd>

                                        <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.createdAt') !!}</dt>
                                        <dd>{{$visitorActivity->created_at}}</dd>

                                    </dl>
                                </li>
                            </ul>
                            <br />
                        </div>

                        <div class="col-md-6 col-lg-4">
                            <ul class="list-group">
                                <li class="list-group-item @if($isClearedEntry) list-group-item-danger @else active @endif">
                                    {!! trans('LaravelVisitorLogger::laravel-logger.drilldown.title-ip-details') !!}
                                </li>
                                <li class="list-group-item">
                                    <dl class="dl-horizontal">
                                        <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.ip') !!}</dt>
                                        <dd>{{$visitorActivity->ipAddress}}</dd>
                                        @if($ipAddressDetails)
                                            @foreach($ipAddressDetails as $ipAddressDetailKey => $ipAddressDetailValue)
                                                <dt>{{$ipAddressDetailKey}}</dt>
                                                <dd>{{$ipAddressDetailValue}}</dd>
                                            @endforeach
                                        @else
                                            <p class="text-center disabled">
                                                <br />
                                                Additional Ip Address Data Not Available.
                                            </p>
                                        @endif
                                    </dl>
                                </li>
                            </ul>

                            <br />
                        </div>

                        <div class="col-md-12 col-lg-4">
                            <ul class="list-group">
                                <li class="list-group-item @if($isClearedEntry) list-group-item-danger @else active @endif">
                                    {!! trans('LaravelVisitorLogger::laravel-logger.drilldown.title-user-details') !!}
                                </li>
                                <li class="list-group-item">
                                    <dl class="dl-horizontal">
                                        <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.userType') !!}</dt>
                                        <dd>
                                            <span class="badge badge-{{$userTypeClass}}">
                                                {{$visitorActivity->userType}}
                                            </span>
                                        </dd>

                                        @if($userDetails)

                                            <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.userId') !!}</dt>
                                            <dd>{{$userDetails->id}}</dd>

                                            @if(config('LaravelVisitorLogger.rolesEnabled'))

                                                <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.labels.userRoles') !!}</dt>

                                                  @foreach ($userDetails->roles as $user_role)

                                                    @if ($user_role->name == 'User')
                                                      @php $labelClass = 'primary' @endphp

                                                    @elseif ($user_role->name == 'Admin')
                                                      @php $labelClass = 'warning' @endphp

                                                    @elseif ($user_role->name == 'Unverified')
                                                      @php $labelClass = 'danger' @endphp

                                                    @else
                                                      @php $labelClass = 'default' @endphp

                                                    @endif

                                                    <dd>
                                                        <span class="badge badge-{{$labelClass}}">
                                                            {{ $user_role->name }} - {!! trans('LaravelVisitorLogger::laravel-logger.drilldown.labels.userLevel') !!} {{ $user_role->level }}
                                                        </span>
                                                    </dd>

                                                  @endforeach

                                            @endif


                                            <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.userName') !!}</dt>
                                            <dd>{{$userDetails->name}}</dd>

                                            <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.userEmail') !!}</dt>
                                            <dd>
                                                <a href="mailto:{{$userDetails->email}}">
                                                    {{$userDetails->email}}
                                                </a>
                                            </dd>

                                            @if($userDetails->last_name || $userDetails->first_name)
                                                <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.userFulltName') !!}</dt>
                                                <dd>{{$userDetails->last_name}}, {{$userDetails->first_name}}</dd>
                                            @endif

                                            @if($userDetails->signup_ip_address)
                                                <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.userSignupIp') !!}</dt>
                                                <dd>{{$userDetails->signup_ip_address}}</dd>
                                            @endif

                                            <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.userCreatedAt') !!}</dt>
                                            <dd>{{$userDetails->created_at}}</dd>

                                            <dt>{!! trans('LaravelVisitorLogger::laravel-logger.drilldown.list-group.labels.userUpdatedAt') !!}</dt>
                                            <dd>{{$userDetails->updated_at}}</dd>

                                        @endif

                                    </dl>
                                </li>
                            </ul>

                            <br />
                        </div>

                    </div>
                </div>
            </div>

            @if(!$isClearedEntry)
                <div class="row">
                    <div class="col-xs-12 col-12">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-info">
                                {!! trans('LaravelVisitorLogger::laravel-logger.drilldown.title-user-activity') !!}
                                <span class="badge">
                                    {{ $totalUserActivities }} {!! trans('LaravelVisitorLogger::laravel-logger.dashboard.subtitle') !!}
                                </span>
                            </li>
                            <li class="list-group-item">
                                @include('LaravelVisitorLogger::logger.partials.activity-table', ['activities' => $userActivities])
                            </li>
                        </ul>
                        <br />
                    </div>
                </div>
            @endif

        </div>
    </div>
  </div>
@endsection