<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.birth_certificate.index']) ? route('member.birth_certificate.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Birth Certificate',
        'href' => $route,
    ],
    [
        'name' => (@$birthCertificate) ? 'Edit' : 'Create',
    ],
];

$data['data'] = [
    'name' => 'Birth Certificate',
    'title'=> (@$birthCertificate) ? 'Edit Birth Certificate' : 'Create Birth Certificate',
    'heading' => 'Birth Certificate',
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
                <h3 class="box-title">Create Birth Certificate</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

           <form method="POST" action="{{(@$birthCertificate) ? route('member.birth_certificate.update',$birthCertificate->id) : route('member.birth_certificate.store')}}" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
            @csrf
            @if(isset($birthCertificate))
            @method('put')
            @endif
            <div class="box-body">

                <div class="col-md-6">

                    <div class="form-group">
                        <label for="name">Newborn Id No <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Newborn Id No" value="{{@$birthCertificate->newborn_id_no}}"  required="" name="newborn_id_no" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Serial No<span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Serial No" value="{{@$birthCertificate->serial_no}}" required="" name="serial_no" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Name<span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Name" value="{{@$birthCertificate->name}}" required="" name="name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Gender <span class="text-red"> * </span> </label>
                        <select id="name" class="form-control" required="" name="sex">
                              @if(isset($subCategory))
                              <option value="{{$birthCertificate->sex}}">{{$birthCertificate->sex}}</option>
                              @else
                              <option value=" ">Select</option>
                              @endif
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="name">Date And Time Of Birth <span class="text-red"> * </span> </label>
                        <input id="datePicker" class="form-control" placeholder="Enter Date And Time Of Birth" value="{{@$birthCertificate->date_and_time_of_birth}}" required="" name="date_and_time_of_birth" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Mother's Id No <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Mother's Id No" value="{{@$birthCertificate->mother_s_id_no}}" required="" name="mother_s_id_no" type="text">
                    </div>


                    <div class="form-group">
                        <label for="name">Mother's Name <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Mother's Name" value="{{@$birthCertificate->mother_s_name}}" required="" name="mother_s_name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Mother's Nationality<span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Mother's Nationality" value="{{@$birthCertificate->mother_s_nationality}}" required="" name="mother_s_nationality" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Mother's Religion <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Mother's Religion" value="{{@$birthCertificate->mother_s_religion}}" required="" name="mother_s_religion" type="text">
                    </div>

                    <div class="form-group">
                        <label for="name">Father's Id No <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Father's Id No" value="{{@$birthCertificate->father_s_id_no}}" required="" name="father_s_id_no" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Father's Name <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Father's Name" value="{{@$birthCertificate->father_s_name}}" required="" name="father_s_name" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Father's Nationality <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Father's Nationality" value="{{@$birthCertificate->father_s_nationality}}" required="" name="father_s_nationality" type="text">
                    </div>

                    <div class="form-group">
                        <label for="name">Father's Religion <span class="text-red"> * </span> </label>
                        <input id="name" class="form-control" placeholder="Enter Father's Religion" value="{{@$birthCertificate->father_s_religion}}" required="" name="father_s_religion" type="text">
                    </div>
                    <div class="form-group">
                        <label for="name">Present Address <span class="text-red"> * </span> </label>
                        <textarea  id="name" class="form-control" placeholder="Enter Present Address"  required="" name="present_address" row="4">{{@$birthCertificate->present_address}}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="name">Permanent Address<span class="text-red"> * </span> </label>
                        <textarea  id="name" class="form-control" placeholder="Enter Permanent Address"  required="" name="permanent_address"  row="4">{{@$birthCertificate->permanent_address}}</textarea>
                    </div>




                </div>

            </div>

            <div class="box-footer">
                <div class="col-md-12">
                @if(isset($birthCertificate))
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
            format: 'DD/MM/YYYY hh:mm A'
            , defaultDate: new Date()
            , showTimezone: true
        , });
    })

</script>
@endpush