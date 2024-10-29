


@if(\Auth::user()->can([$route.'.edit']))
<a href="{{ route($route . '.edit', $model->id) }}" class="btn btn-xs btn-success">
    <i class="fa fa-pencil"></i>
</a>

@endif
@if(\Auth::user()->can([$route.'_commissions.create']))
<a class="btn btn-xs btn-success"
    href="{{ route($route.'_commissions.create') }}?marketing_officer_id={{$model->id}}"><i
        class="fa fa-plus-circle" title='Add comission'></i>
    </a>


@endif

@if(\Auth::user()->can([$route.'_commissions.show']))
<a class="btn btn-xs btn-success"
    href="{{ route($route.'_commissions.show',$model->id) }}"><i
        class="fa fa-eye" title='Comission Show'></i>
    </a>


@endif
@if(\Auth::user()->can([$route . '.destroy']))
<a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route($route . '.destroy', $model->id) }}">
    <i class="fa fa-times"></i>
</a>
@endif
