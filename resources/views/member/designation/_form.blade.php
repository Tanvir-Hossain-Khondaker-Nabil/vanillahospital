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
        <label for="name">{{__('common.designation_name')}} <span class="text-red"> * </span> </label>
        {!! Form::text('name',null,['id'=>'name','class'=>'form-control','placeholder'=>trans('common.enter_name'), 'required']); !!}
    </div>


    <div class="form-group">
        <label for="name">{{__('common.salary')}}  <span class="text-red"> * </span> </label>
        {!! Form::number('salary',null,['id'=>'salary','class'=>'form-control','step'=>'any','placeholder'=>'Enter  salary', 'required']); !!}
    </div>
    <div class="form-group">
        <label for="name">{{__('common.commission_percentage')}} </label>
        {!! Form::number('commission_percentage',null, ['id'=>'commission_percentage','class'=>'form-control','placeholder'=>trans('common.commission_percentage'), 'step'=>'any']); !!}
    </div>
    <div class="form-group">
        <label for="status">{{__('common.commission_tags_area')}}   </label>
        {!! Form::select('commission_area', get_commission_areas(), null,['class'=>'form-control','placeholder'=>'Select commission Tag Area']); !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="parent_designation_id">{{__('common.parent_designation')}}   </label>
        {!! Form::select('parent_designation_id', $designations, null,['id'=>'parent_designation_id','class'=>'form-control select2','placeholder'=>trans('common.please_select')]); !!}
    </div>
    <div class="form-group">
        <label for="parent_designation_id">{{__('common.department')}}   <span class="text-red"> * </span></label>
        {!! Form::select('department_id', $departments, null,['id'=>'department_id','class'=>'form-control select2','placeholder'=>'Select Department','required']); !!}
    </div>
    <div class="form-group">
        <label for="status">{{__('common.type')}}   ({{__('common.optional')}} )  </label>
        {!! Form::select('type',['1'=>'Special', '0'=>'General'], 0,['id'=>'status','class'=>'form-control']); !!}
    </div>
    <div class="form-group">
        <label for="status">{{__('common.status')}}   </label>
        {!! Form::select('active_status',['1'=>'Active', '0'=>'Inactive'], null,['id'=>'status','class'=>'form-control', 'required']); !!}
    </div>
</div>


