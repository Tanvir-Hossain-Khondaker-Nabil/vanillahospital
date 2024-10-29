<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 3/12/2023
 * Time: 3:42 PM
 */

?>


@if(\Auth::user()->can(['member.doctors.edit']))
<a class="btn btn-xs btn-success" href="{{ route('member.vehicle_detail.edit',$model->id) }}"><i class="fa fa-edit" title='Edit'></i>
</a>
@endif

<a class="btn btn-xs btn-success" href="{{ asset('public/'.$model->patient_name.'-'.$model->invoice_number.'.pdf') }}"><i class="fa-regular fa-file"></i>
</a>

<a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm" data-target="{{ route($route . '.destroy', $model->id) }}">
    <i class="fa fa-times"></i>
</a>


