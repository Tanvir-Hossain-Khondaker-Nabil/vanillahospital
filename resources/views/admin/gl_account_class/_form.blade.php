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
            <label for="name">Name <span class="text-red"> * </span> </label>
            {!! Form::text('name', null, ['id'=>'name','class'=>'form-control','placeholder'=>'Enter Name', 'required']); !!}
        </div>
        <div class="form-group">
            <label for="name">Class Type <span class="text-red"> * </span> </label>
            {!! Form::select('class_type',$class_type, null,['id'=>'class_type','class'=>'form-control','placeholder'=>'Enter Class Type', 'required']); !!}
        </div>
    </div>
