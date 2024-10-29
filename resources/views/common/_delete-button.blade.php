
@if ($model->reply_status == 'yes' && \Auth::user()->can([$route . '.show']) )
<a href="{{ route($route . '.show', $model->id) }}" class="btn btn-xs btn-info ">
    <i class="fa fa-info-circle"></i>
</a>
@endif


@if(\Auth::user()->can([$route . '.destroy']))
<a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route($route . '.destroy', $model->id) }}">
    <i class="fa fa-times"></i>
</a>
@endif
