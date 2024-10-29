<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */

?>
    <div class="col-md-7">

        <div class="form-group {{ $errors->has('membership_id') ? 'has-error' : '' }}">
            <label for="membership_id">Select Memberships  <span class="text-red"> * </span>  </label>
            {!! Form::select('membership_id', $memberships , null,['id'=>'membership_id','class'=>'form-control','placeholder'=>'Please select Memberships System']); !!}
        </div>

        <div class="form-group  {{ $errors->has('full_name') ? 'has-error' : '' }} ">
            <label for="full_name">Member Full Name <span class="text-red"> * </span> </label>
            {!! Form::text('full_name',isset($user) ? $user->full_name : null,['id'=>'full_name','class'=>'form-control','placeholder'=>'Enter Full Name', 'required']); !!}
        </div>

        <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
            <label for="email">Email  <span class="text-red"> * </span>  </label>
            {!! Form::text('email',isset($user) ? $user->email : null,['id'=>'email','class'=>'form-control','placeholder'=>'Enter Email', 'required']); !!}
        </div>
        <div class="form-group">
            <label for="phone"> Phone <span class="text-red"> * </span> </label>
            {!! Form::text('phone', isset($user) ? $user->phone : null,['id'=>'phone','class'=>'form-control input-number','placeholder'=>'Enter Phone', 'required']); !!}
        </div>

        @if(!$user->company_id)
        <div class="form-group">
            <label for="name">Company Name<span class="text-red"> * </span> </label>
            {!! Form::text('company_name',null,['id'=>'company_name','class'=>'form-control','placeholder'=>'Enter Company Name', 'required']); !!}
        </div>

        <div class="form-group">
            <label for="display_name">Company Address <span class="text-red"> * </span> </label>
            {!! Form::text('address',null,['id'=>'address','class'=>'form-control','placeholder'=>'Enter Address',  'rows'=>"2"]); !!}
        </div>
        <div class="form-group {{ $errors->has('country_id') ? 'has-error' : '' }}">
            <label for="country_id">Select Country  <span class="text-red"> * </span>  </label>
            {!! Form::select('country_id', $countries , null,['id'=>'country_id','class'=>'form-control','placeholder'=>'Please select Country']); !!}
        </div>
        @endif
        <div class="form-group">
            <label for="status">Status <span class="text-red"> * </span>  </label>
            {!! Form::select('status',['active'=>'Active', 'inactive'=>'Inactive'], null,['id'=>'status','class'=>'form-control','placeholder'=>'Please select status', 'required']); !!}
        </div>
    </div>
