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
            <label for="display_text">Title <span class="text-red"> * </span> </label>
            {!! Form::text('display_text',null,['id'=>'display_text','class'=>'form-control','placeholder'=>'Enter Title', 'required']); !!}
        </div>
        <div class="form-group">
            <label for="description">Description <span class="text-red"> * </span> </label>
            {!! Form::textarea('description',null,['id'=>'description','class'=>'form-control','placeholder'=>'Enter description', 'required']); !!}
        </div>
        <div class="form-group">
            <label for="time_period">Time Period <span class="text-red"> * </span> </label>
            {!! Form::number('time_period',null,['id'=>'time_period','class'=>'form-control','placeholder'=>'Enter Time period (monthly count)', 'required']); !!}
        </div>
        <div class="form-group">
            <label for="price">Price <span class="text-red"> * </span> </label>
            {!! Form::number('price',null,['id'=>'price','class'=>'form-control','placeholder'=>'Enter price', 'required']); !!}
        </div>
        <div class="form-group">
            <label for="price">Total Users (Can use) <span class="text-red"> * </span> </label>
            {!! Form::number('total_users',null,['id'=>'price','class'=>'form-control','placeholder'=>'Enter Total Users', 'required']); !!}
        </div>
        <div class="form-group">
            <label for="discount">Discount <span> (optional) </span> </label>
            {!! Form::number('discount',null,['id'=>'display_text','class'=>'form-control','placeholder'=>'Enter Discount']); !!}
        </div>
        <div class="form-group">
            <label for="discount_type">Select Discount Type <span> (optional) </span>  </label>
            {!! Form::select('discount_type',['Percentage'=>'Percentage', 'Fixed'=>'Fixed'], null,['id'=>'discount_type','class'=>'form-control','placeholder'=>'Please select discount type']); !!}
        </div>
        <div class="form-group">
            <label for="status">Status <span class="text-red"> * </span>  </label>
            {!! Form::select('status',['active'=>'Active', 'inactive'=>'Inactive'], null,['id'=>'status','class'=>'form-control','placeholder'=>'Please select status', 'required']); !!}
        </div>
    </div>
