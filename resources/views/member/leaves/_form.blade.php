<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

?>


<div class="col-md-7">


    <div class="form-group">
        <label for="display_name">Title<span class="text-red"> * </span> </label>
        {!! Form::text('title',null,['id'=>'title','class'=>'form-control','placeholder'=>'Enter Title','required']); !!}
    </div>


    <div class="form-group">
        <label for="display_name">Note <span class="text-red"> * </span> </label>
        {!! Form::text('note',null,['id'=>'note','class'=>'form-control','placeholder'=>'Enter note','required']); !!}
    </div>

    <div class="form-group">
        <label for="display_name">Status </label>
        {!! Form::select('status',['1'=>'Active','0'=>'Inactive'],null,['id'=>'status','class'=>'form-control','required']); !!}
    </div>

</div>


