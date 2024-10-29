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
        <label for="name">{{__('common.brand_name')}}<span class="text-red"> * </span> </label>
        {!! Form::text('name',null,['id'=>'name','class'=>'form-control','placeholder'=>trans('common.enter_brand_name'), 'required']); !!}
    </div>
</div>



