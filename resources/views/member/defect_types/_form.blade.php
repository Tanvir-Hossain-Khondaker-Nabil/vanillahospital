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
        <label for="name">{{__('common.name')}}<span class="text-red"> * </span> </label>
        {!! Form::text('name',null,['id'=>'name','class'=>'form-control','placeholder'=>trans('common.name'), 'required']); !!}
    </div>
</div>

<div class="col-md-6">
    <div class="form-group">
        <label for="status">{{__('common.status')}}  </label>
        {!! Form::select('status',['active'=>'Active', 'inactive'=>'Inactive'], null,['id'=>'status','class'=>'form-control', 'required']); !!}
    </div>
</div>

