<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

?>



<div class="col-md-6">
    <div class="form-group">
        <label for="name">{{__('common.service_name')}}<span class="text-red"> * </span> </label>
        {!! Form::text('title',null,['id'=>'title','class'=>'form-control','placeholder'=>trans('common.enter_service_name'), 'required']); !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="name">{{__('common.price')}} </label>
        {!! Form::number('price',null,['id'=>'price','class'=>'form-control','step'=>'any','placeholder'=>trans('common.price')]); !!}
    </div>
</div>


<div class="col-md-6">
    <div class="form-group">
        <label for="status">{{__('common.status')}}  </label>
        {!! Form::select('status',['active'=>'Active', 'inactive'=>'Inactive'], null,['id'=>'status','class'=>'form-control', 'required']); !!}
    </div>
</div>

