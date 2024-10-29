<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.death_certificate.index']) ? route('member.death_certificate.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Death Certificate',
        'href' => $route,
    ],
    [
        'name' => (@$deathCertificate) ? 'Edit' : 'Create',
    ],
];

$data['data'] = [
    'name' => 'Death Certificate',
    'title'=> (@$deathCertificate) ? 'Edit Death Certificate' : 'Create Death Certificate',
    'heading' => 'Death Certificate',
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
                <h3 class="box-title">Create Death Certificate</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

           <form method="POST" action="{{(@$deathCertificate) ? route('member.death_certificate.update',$deathCertificate->id) : route('member.death_certificate.store')}}" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
            @csrf
            @if(isset($deathCertificate))
            @method('put')
            @endif
            <div class="box-body">

                <div class="col-md-6">

                    <div class="form-group">
                        <label for="name">Death Id No <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Death Id No" value="{{@$deathCertificate->death_id_no}}"  required="" name="death_id_no" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Serial No<span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Serial No" value="{{@$deathCertificate->serial_no}}" required="" name="serial_no" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Name<span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Name" value="{{@$deathCertificate->name}}" required="" name="name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Date Of Birth <span class="text-red"> * </span> </label>
                        <input id="datePicker" class="form-control" placeholder="Enter Date Of Birth" value="{{@$deathCertificate->date_of_birth}}" required="" name="date_of_birth" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Date Of Death <span class="text-red"> * </span> </label>
                        <input id="datePicker" class="form-control" placeholder="Enter Date Of Death" value="{{@$deathCertificate->date_of_death}}" required="" name="date_of_death" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Gender <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Gender " value="{{@$deathCertificate->sex}}" required="" name="sex" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Gender <span class="text-red"> * </span> </label>
                        <select id="name" class="form-control" required="" name="sex">
                              @if(isset($subCategory))
                              <option value="{{$deathCertificate->sex}}">{{$deathCertificate->sex}}</option>
                              @else
                              <option value=" ">Select</option>
                              @endif
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Place Of Death <span class="text-red"> * </span> </label>
                        <textarea  id="name" class="form-control" placeholder="Enter Place Of Death "  required="" name="place_of_death" row="4">{{@$deathCertificate->place_of_death}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="name">Cause Of Death <span class="text-red"> * </span> </label>
                        <textarea  id="name" class="form-control" placeholder="Enter Cause Of Death "  required="" name="cause_of_death" row="4">{{@$deathCertificate->cause_of_death}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="name">Mother's Name <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Mother's Name" value="{{@$deathCertificate->mother_s_name}}" required="" name="mother_s_name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Father's Name <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Father's Name" value="{{@$deathCertificate->father_s_name}}" required="" name="father_s_name" type="text">
                    </div>                    
                    
                    <div class="form-group">
                        <label for="name">Permanent Address<span class="text-red"> * </span> </label>
                        <textarea  id="name" class="form-control" placeholder="Enter Permanent Address"  required="" name="permanent_address"  row="4">{{@$deathCertificate->permanent_address}}</textarea>
                    </div>




                </div>

            </div>

            <div class="box-footer">
                <div class="col-md-12">
                @if(isset($deathCertificate))
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
@push('scripts')
<!-- datetime picker -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/css/bootstrap-datetimepicker.min.css" />

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/js/bootstrap-datetimepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $("#datePicker").datetimepicker({
            format: 'DD-MM-YYYY'
            , defaultDate: new Date()
            , showTimezone: true
        , });
    })

</script>
@endpush