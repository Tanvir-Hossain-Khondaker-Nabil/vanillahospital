<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 3/12/2023
 * Time: 3:42 PM
 */

?>


@if(\Auth::user()->can([$route . '.edit']))
<a href="{{ route($route . '.edit', $model->id) }}" class="btn btn-xs btn-success">
    <i class="fa fa-pencil"></i>
</a>
@endif
