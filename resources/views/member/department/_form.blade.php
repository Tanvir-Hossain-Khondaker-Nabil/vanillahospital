<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

?>

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush


<div class="col-md-6">
    <div class="form-group">
        <label for="name">{{__('common.department_name')}}  <span class="text-red"> * </span> </label>
        {!! Form::text('name',null,['id'=>'name','class'=>'form-control','placeholder'=>trans('common.enter_department'), 'required']); !!}
    </div>

    <div class="form-group">
        <label for="status">{{__('common.status')}}   </label>
        {!! Form::select('active_status',['1'=>'Active', '0'=>'Inactive'], null,['id'=>'status','class'=>'form-control', 'required']); !!}
    </div>
</div>


