<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.driver.index']) ? route('member.driver.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Driver',
        'href' => $route,
    ],
    [
        'name' => (@$driver) ? 'Edit' : 'Create',
    ],
];

$data['data'] = [
    'name' => 'Birth Certificate',
    'title'=> (@$driver) ? 'Edit Driver' : 'Create Driver',
    'heading' => 'Driver',
];

?>



@extends('layouts.back-end.master', $data)

@section('contents')
<div class="row">
    <!-- left column -->
    <div class="col-md-12">

        @include('common._alert')

        @include('common._error')
        <!-- general form elements -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Create Driver</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

           <form method="POST" action="{{(@$driver) ? route('member.driver.update',$driver->id) : route('member.driver.store')}}" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
            @csrf
            @if(isset($driver))
            @method('put')
            @endif
            <div class="box-body">

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name<span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Name" value="{{@$driver->name}}" required="" name="name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Email <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Email" value="{{@$driver->email}}" required="" name="email" type="email">
                    </div>
                    <div class="form-group">
                        <label for="name">First Mobile Number <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Phone" value="{{@$driver->phone_one}}" required="" name="phone_one" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Second Mobile Number <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Phone" value="{{@$driver->phone_two}}" required="" name="phone_two" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Driving License No <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Driving License No" value="{{@$driver->driving_license_no}}" required="" name="driving_license_no" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Address<span class="text-red"> * </span> </label>
                        <textarea  id="name" class="form-control" placeholder="Enter Address"  required="" name="address"  row="4">{{@$driver->address}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="name">Status <span class="text-red"> * </span> </label>
                        <select id="name" class="form-control" required="" name="status">
                              @if(isset($driver))
                              <option value="{{$driver->status}}">{{$driver->status  == 1 ? 'Active' : 'Inactive'}}</option>
                              @else
                              <option value=" ">Select</option>
                              @endif
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="box-footer">
                <div class="col-md-12">
                @if(isset($driver))
                    <button type="submit" class="btn btn-primary">Update</button>
                @else    
                    <button type="submit" class="btn btn-primary">Submit</button>
                @endif
                    
                </div>
            </div>
            <!-- /.box-body -->

            </form>
            <!-- /.box -->
        </div>
    </div>
</div>
@endsection
