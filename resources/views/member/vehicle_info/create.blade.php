<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.vehicle_info.index']) ? route('member.vehicle_info.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Vehicle Info',
        'href' => $route,
    ],
    [
        'name' => (@$vehicleInfo) ? 'Edit' : 'Create',
    ],
];

$data['data'] = [
    'name' => 'Vehicle Info',
    'title'=> (@$vehicleInfo) ? 'Edit Vehicle Info' : 'Create Vehicle Info',
    'heading' => 'Vehicle Info',
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
                <h3 class="box-title">Create Vehicle Info</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

           <form method="POST" action="{{(@$vehicleInfo) ? route('member.vehicle_info.update',$vehicleInfo->id) : route('member.vehicle_info.store')}}" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
            @csrf
            @if(isset($vehicleInfo))
            @method('put')
            @endif
            <div class="box-body">

                <div class="col-md-6">    
                    <div class="form-group">
                        <label for="name">Model No<span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Model No" value="{{@$vehicleInfo->model_no}}" required="" name="model_no" type="text">
                    </div>                    
                    <div class="form-group">
                        <label for="name">Model Year<span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Model Year" value="{{@$vehicleInfo->model_year}}" required="" name="model_year" type="text">
                    </div>
                    <div class="form-group">                    
                        <label for="name">Gate Pass Year <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Gate Pass Year" value="{{@$vehicleInfo->gate_pass_year}}"  required="" name="gate_pass_year" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Chassis No<span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Chassis No" value="{{@$vehicleInfo->chassis_no}}" required="" name="chassis_no" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Engine No <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Engine No" value="{{@$vehicleInfo->engine_no}}" required="" name="engine_no" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Status <span class="text-red"> * </span> </label>
                        <select id="name" class="form-control" required="" name="status">
                              @if(isset($vehicleInfo))
                              <option value="{{$vehicleInfo->status}}">{{$vehicleInfo->status  == 1 ? 'Active' : 'Inactive'}}</option>
                              @else
                              <option value=" ">Select</option>
                              @endif
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Vehicle Document <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Vehicle Document" value="{{@$vehicleInfo->vehicle_doc}}" required="" name="vehicle_doc" type="text">
                    </div>
                    




                </div>

            </div>

            <div class="box-footer">
                <div class="col-md-12">
                @if(isset($vehicleInfo))
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
