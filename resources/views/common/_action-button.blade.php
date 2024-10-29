<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/28/2019
 * Time: 12:42 PM
 */
?>

@if (isset($record) && !empty($record))
    {!! $record !!}
@endif
{{--<a href="javascript:void(0);" class="btn btn-xs btn-info ajax-show" data-target="{{ route($route . '.show', $model->id) }}">--}}
    {{--<i class="fa fa-info-circle"></i>--}}
{{--</a>--}}



@if(\Auth::user()->can([$route.'.edit']))
<a href="{{ route($route . '.edit', $model->id) }}" class="btn btn-xs btn-success">
    <i class="fa fa-pencil"></i>
</a>
@endif


@if(\Auth::user()->can([$route.'.destroy']))

    <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route($route . '.destroy', $model->id) }}">
        <i class="fa fa-times"></i>
    </a>

@endif
