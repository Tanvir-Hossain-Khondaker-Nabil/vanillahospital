<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.live_consultation.index']) ? route('member.live_consultation.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Live Consultation',
        'href' => $route,
    ],
    [
        'name' => (@$vehicleInfo) ? 'Edit' : 'Create',
    ],
];

$data['data'] = [
    'name' => 'Live Consultation',
    'title'=> (@$vehicleInfo) ? 'Edit Live Consultation' : 'Create Live Consultation',
    'heading' => 'Live Consultation',
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
                <h3 class="box-title">Create Live Consultation</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            <form method="POST" action="{{(@$vehicleInfo) ? route('member.live_consultation.update',$vehicleInfo->id) : route('member.live_consultation.store')}}" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                @csrf
                @if(isset($vehicleInfo))
                @method('put')
                @endif
                <div class="box-body">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Consultation Title<span class="text-red"> * </span> </label>
                            <input id="name" class="form-control" placeholder="Enter Consultation Title" value="{{@$vehicleInfo->title}}" required="" name="title" type="text">
                        </div>
                        <div class="form-group">
                            <label for="name">Consultation Date<span class="text-red"> * </span> </label>
                            <input id="name" class="form-control" placeholder="Enter Consultation Date" value="{{@$vehicleInfo->date}}" required="" name="date" type="text">
                        </div>
                        <div class="form-group">
                            <label for="name">Consultation Duration Minutes <span class="text-red"> * </span> </label>
                            <input id="name" class="form-control" placeholder="Enter Consultation Duration Minutes" value="{{@$vehicleInfo->duration}}" required="" name="duration" type="number">
                        </div>
                        <div class="form-group">
                            <label for="name">Consultant Doctor<span class="text-red"> * </span> </label>
                            <select id="vehicle_info_register_id" class="form-control vehicle_info_register_id" name="doctor_id">
                                <option value="">Select</option>
                                @foreach($doctors as $key=>$doctor)
                                <option value="{{$key}}">{{$doctor}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group pt-5">
                            <input type="radio" id="ipd" name="radio">
                            <label for="ipd" class="mr-4 not-blur-radio-button">IPD</label>
                            <input type="radio" id="outdoor" name="radio">
                            <label for="outdoor" class="mr-4 not-blur-radio-button">Outdoor</label>
                            <input type="radio" id="emergence" name="radio">
                            <label for="emergence" class="mr-4 not-blur-radio-button">Emergency</label>
                            <input type="radio" id="normal" name="radio">
                            <label for="normal" class="mr-4 not-blur-radio-button">Normal</label>
                        </div>
                        <div id="ipd_form" class="d-none">
                            <div class="form-group">
                                <label for="name">Name<span class="text-red"> * </span> </label>
                                <select class="form-control" id="ipd_patient_info_registration_id" name="ipd_patient_info_registration_id">

                                    <option>Select</option>
                                    @foreach($ipd_patient_infos as $key=>$ipd_patient_info)
                                    <option value="{{$key}}">{{$ipd_patient_info}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="ipd_patient_info_form_id">
                            </div>
                        </div>
                        <div id="outdoor_form" class="d-none">
                            <div class="form-group">
                                <label for="name">Name<span class="text-red"> * </span> </label>
                                <select class="form-control" id="outdoor_registration_id" name="outdoor_registration_id">

                                    <option>Select</option>
                                    @foreach($outdoor_registrations as $key=>$outdoor_registration)
                                    <option value="{{$key}}">{{$outdoor_registration}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div id="outdoor_form_id">
                            </div>
                        </div>
                        <div id="emergence_form" class="d-none">
                            Emergency
                        </div>
                        <div id="normal_form" class="d-none">
                            <div class="form-group">
                                <label for="name">Name<span class="text-red"> * </span> </label>
                                <input class="form-control" placeholder="Enter Name" id="patient_name" name="patient_name" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Email</label>
                                <input class="form-control" placeholder="Enter Email" name="patient_email" id="patient_email" type="email">
                            </div>

                            <div class="form-group">
                                <label for="name">Mobile Number <span class="text-red"> * </span> </label>
                                <input class="form-control" placeholder="Enter Mobile Number" id="patient_phone_one" name="patient_phone_one" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Gender</label>
                                <input class="form-control" placeholder="Enter Gender" name="gender" type="text">
                            </div>

                            <div class="form-group">
                                <label for="name">Date of Birth</label>
                                <input class="form-control datePicker" placeholder="Enter Date of Birth" name="date_of_birth" type="text">
                            </div>

                            <div class="form-group">
                                <label for="name">Age</label>
                                <input class="form-control" placeholder="Enter Age" name="age" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Blood Group</label>
                                <input class="form-control" placeholder="Enter blood_group" name="blood_group" type="text">
                            </div>
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
@push('scripts')
<script>
    $('#normal').click(function() {
        $('#normal_form').show()
        $('#ipd_form').remove()
        $('#outdoor_form').remove()
        $('#emergence_form').remove()

        $('.not-blur-radio-button').addClass('blur-radio-button');
        $('#normal').removeClass('blur-radio-button');

        $('#ipd').attr('disabled', 'disabled');
        $('#outdoor').attr('disabled', 'disabled');
        $('#emergence').attr('disabled', 'disabled');
    });
    $('#ipd').click(function() {
        $('#ipd_form').show()
        $('#outdoor_form').remove()
        $('#emergence_form').remove()
        $('#normal_form').remove()

        $('.not-blur-radio-button').addClass('blur-radio-button');
        $('#ipd').removeClass('blur-radio-button');

        $('#normal').attr('disabled', 'disabled');
        $('#outdoor').attr('disabled', 'disabled');
        $('#emergence').attr('disabled', 'disabled');

    });
    $('#outdoor').click(function() {
        $('#outdoor_form').show()
        $('#normal_form').remove()
        $('#ipd_form').remove()
        $('#emergence_form').remove()

        $('.not-blur-radio-button').addClass('blur-radio-button');
        $('#outdoor').removeClass('blur-radio-button');

        $('#normal').attr('disabled', 'disabled');
        $('#ipd').attr('disabled', 'disabled');
        $('#emergence').attr('disabled', 'disabled');

    });
    $('#emergence').click(function() {
        $('#emergence_form').show()
        $('#normal_form').remove()
        $('#ipd_form').remove()
        $('#outdoor_form').remove()

        $('.not-blur-radio-button').addClass('blur-radio-button');
        $('#emergence').removeClass('blur-radio-button');

        $('#normal').attr('disabled', 'disabled');
        $('#ipd').attr('disabled', 'disabled');
        $('#outdoor').attr('disabled', 'disabled');
    });


    $('#outdoor_registration_id').on('change', async function() {
        let outdoor_registration_id = $(this).val()


        const response = await fetch('/member/outdoor_registration_id/' + outdoor_registration_id, {
            method: "GET"
        , });
        const result = await response.json();


        result.map((result, index) => (
            $('#outdoor_form_id').html(`
                <input  value="${result.patient_name}" class="input" id="patient_name"  name="patient_name" type="hidden">
                <div class="form-group">
                    <label for="name">Email</label>
                    <input class="form-control" placeholder="Enter Email" name="patient_email" id="patient_email" type="email">
                </div>
                <div class="form-group">
                    <label for="name">Mobile Number <span class="text-red"> * </span> </label>
                    <input class="form-control" placeholder="Enter Mobile Number" value="${result?.phone ?? ''}"  id="patient_phone_one"  name="patient_phone_one" type="text">
                </div>
                <div class="form-group">
                    <label for="name">Gender <span class="text-red"> * </span> </label>
                    <input class="form-control" placeholder="Enter Gender" value="${result?.gender ?? ''}"  name="gender" type="text">
                </div>
                <div class="form-group">
                    <label for="name">Age <span class="text-red"> * </span> </label>
                    <input class="form-control" placeholder="Enter Age" value="${result?.age ?? ''}"  name="age" type="text">
                </div> 
                <div class="form-group">
                    <label for="name">Date of Birth</label>
                    <input class="form-control datePicker_class" onclick="rating()" placeholder="Enter Date of Birth" name="date_of_birth" type="text">
                </div>
                <div class="form-group">
                    <label for="name">Blood Group</label>
                    <input class="form-control" placeholder="Enter blood_group" name="blood_group" type="text">
                </div>
                `)
        ))

    })

    $('#ipd_patient_info_registration_id').on('change', async function() {
        let ipd_patient_info_registration_id = $(this).val()


        const response = await fetch('/member/ipd_patient_info_registration_id/' + ipd_patient_info_registration_id, {
            method: "GET"
        , });
        const result = await response.json();


        result.map((result, index) => (
            $('#ipd_patient_info_form_id').html(`
                <input  value="${result.patient_name}" class="input" id="patient_name" name="patient_name" type="hidden">
                <div class="form-group">
                    <label for="name">Email</label>
                    <input  class="form-control" placeholder="Enter Phone" value="${result?.email ?? ''}"  name="patient_email" type="text">
                </div>
                <div class="form-group">
                    <label for="name">Mobile Number <span class="text-red"> * </span> </label>
                    <input  class="form-control" placeholder="Enter Mobile Number" value="${result?.phone ?? ''}"  id="patient_phone_one"  name="patient_phone_one" type="text">
                </div>
                <div class="form-group">
                    <label for="name">Gender <span class="text-red"> * </span> </label>
                    <input  class="form-control" placeholder="Enter Gender" value="${result?.gender ?? ''}"  name="gender" type="text">
                </div>
                <div class="form-group">
                    <label for="name">Date of Birth <span class="text-red"> * </span> </label>
                    <input  class="form-control datePicker_class" onclick="rating()" placeholder="Enter Date of Birth" value="${result?.date_of_birth ?? ''}"  name="date_of_birth" type="text">
                </div>
                <div class="form-group">
                    <label for="name">Age <span class="text-red"> * </span> </label>
                    <input class="form-control" placeholder="Enter Age" value="${result?.age ?? ''}"  name="age" type="text">
                </div>
                <div class="form-group">
                    <label for="name">Blood Group <span class="text-red"> * </span> </label>
                    <input class="form-control" placeholder="Enter blood_group" value="${result?.blood_group ?? ''}"  name="blood_group" type="text">
                </div>
                `)

        ))
    })

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.4/js/bootstrap-datetimepicker.min.js"></script>
<script>
    $(document).ready(function() {
        $(".datePicker").datetimepicker({
            format: 'DD/MM/YYYY'
            , defaultDate: new Date()
            , showTimezone: true
        , });
    })

    function rating() {
        $(".datePicker_class").datetimepicker({
            format: 'DD/MM/YYYY'
            , defaultDate: new Date()
            , showTimezone: true
        , });
    }

</script>
@endpush
