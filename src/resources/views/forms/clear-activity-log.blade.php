{!! Form::open(array('route' => 'clear-activity')) !!}
    {!! Form::hidden('_method', 'DELETE') !!}
    {!! Form::button('<i class="fa fa-fw fa-trash" aria-hidden="true"></i>' . trans('LaravelVisitorLogger::laravel-logger.dashboard.menu.clear'), array('type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => trans('LaravelVisitorLogger::laravel-logger.modals.clearLog.title'),'data-message' => trans('LaravelVisitorLogger::laravel-logger.modals.clearLog.message'), 'class' => 'dropdown-item')) !!}
{!! Form::close() !!}
