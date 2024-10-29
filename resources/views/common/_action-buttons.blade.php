<?php


?>
@if (isset($project_task) && !empty($project_task) && \Auth::user()->can(['member.project_wise_task']))
<a href="{{ route('member.project_wise_task', $model->id) }}" class="btn btn-xs btn-info ">
    <i class="fa fa-eye"></i>
</a>
@endif

@if(\Auth::user()->can([$route . '.show']))
<a href="{{ route($route . '.show', $model->id) }}" class="btn btn-xs btn-info">
    <i class="fa fa-info-circle"></i>
</a>

@endif

@if(isset($kanban) && !empty($kanban))
<a href="{{ route('member.users.kanban_list', $model->id) }}" class="btn btn-xs btn-info">
    <i class="fa fa-tasks"></i>
</a>
@endif


@if(\Auth::user()->can([$route.'.edit']))
<a href="{{ route($route . '.edit', $model->id) }}" class="btn btn-xs btn-success">
    <i class="fa fa-pencil"></i>
</a>

@endif

@if(\Auth::user()->can([$route . '.destroy']))
<a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route($route . '.destroy', $model->id) }}">
    <i class="fa fa-times"></i>
</a>
@endif
