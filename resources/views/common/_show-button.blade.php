

@if(\Auth::user()->can([$route . '.show']))
<a href="{{ route($route . '.show', $model->id) }}" class="btn btn-xs btn-info ">
    <i class="fa fa-info-circle"></i>
</a>

@endif
