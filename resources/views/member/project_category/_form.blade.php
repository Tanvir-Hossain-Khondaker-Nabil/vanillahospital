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

    <div class="form-group  {{ $errors->has('display_name') ? 'has-error' : '' }} ">
        <label for="name">Name <span class="text-red"> * </span> </label>
        {!! Form::text('display_name', null, ['id'=>'display_name','class'=>'form-control','placeholder'=>'Enter Name', 'required']); !!}
    </div>

    <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
        <label for="status">Select Status  </label>
        {!! Form::select('status', ['active'=>'Active', 'inactive'=>'Inactive'] , null,['id'=>'status','class'=>'form-control']); !!}
    </div>
</div>


