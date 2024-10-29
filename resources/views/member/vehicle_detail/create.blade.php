<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.vehicle_detail.index']) ? route('member.vehicle_detail.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Vehicle Detail',
        'href' => $route,
    ],
    [
        'name' => (@$vehicleDetail) ? 'Edit' : 'Create',
    ],
];

$data['data'] = [
    'name' => 'Vehicle Detail',
    'title'=> (@$vehicleDetail) ? 'Edit Ambulance Booking' : 'Create Ambulance Booking',
    'heading' => 'Ambulance Booking',
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
                <ul id="breadcrumb" style="display: none">
                </ul>
                <h3 class="box-title">Create Ambulance Booking</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form method="POST" id="save_form" action="{{(@$vehicleDetail) ? route('member.vehicle_detail.update',$vehicleDetail->id) : route('member.vehicle_detail.store')}}" accept-charset="UTF-8" role="form" enctype="multipart/form-data">
                @csrf
                @if(isset($vehicleDetail))
                @method('put')
                @endif
                <div class="box-body">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Driver Name<span class="text-red"> * </span> </label>
                            <select id="driver_register_id" class="form-control driver_register_id" name="driver_id">

                                <option value="">Select</option>
                                @foreach($drivers as $key=>$driver)
                                <option value="{{$key}}">{{$driver}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Ambulance Name<span class="text-red"> * </span> </label>
                            <select id="vehicle_info_register_id" class="form-control vehicle_info_register_id" name="vehicle_info_id">
                                <option value="">Select</option>
                                @foreach($vehicleInfos as $key=>$vehicle_info)
                                <option value="{{$key}}">{{$vehicle_info}}</option>
                                @endforeach
                            </select>
                        </div>

                        <label for="name">Ambulance Schedule<span class="text-red"> * </span> </label><br>
                        <div class="schedule"></div>

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
                                <label for="name">First Mobile Number <span class="text-red"> * </span> </label>
                                <input class="form-control" placeholder="Enter Mobile Number" id="patient_phone_one" name="patient_phone_one" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Second Mobile Number</label>
                                <input class="form-control" placeholder="Enter Mobile Number" name="patient_phone_two" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Gender</label>
                                <input class="form-control" placeholder="Enter Gender" name="gender" type="text">
                            </div>

                            <div class="form-group">
                                <label for="name">Date of Birth</label>
                                <input class="form-control datePicker_class"  onclick="rating()" placeholder="Enter Date of Birth" name="date_of_birth" type="text">
                            </div>

                            <div class="form-group">
                                <label for="name">Age</label>
                                <input class="form-control" placeholder="Enter Age" name="age" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Blood Group</label>
                                <input class="form-control" placeholder="Enter blood_group" name="blood_group" type="text">
                            </div>

                            <div class="form-group">
                                <label for="name">Date and Time <span class="text-red"> * </span> </label>
                                <input class="form-control datePicker start_date_and_time" placeholder="Enter Date and Time" id="start_date_and_time" name="start_date_and_time" type="text">
                            </div>
                            <div class="form-group">
                                <label for="name">Address<span class="text-red"> * </span> </label>
                                <textarea class="form-control patient_address" placeholder="Enter Address" id="patient_address" name="patient_address" row="4"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <table style="width: 100%" class="sales_table_2">
                            <tr>
                                <td class="total-line">Price</td>
                                <td class="total-value text-right">
                                    <input type="text" class="form-control price" placeholder="Price" id="price_amount" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="total-line">Discount</td>
                                <td class="total-value text-right">
                                    <div id="discount-section">
                                        <input type="number" placeholder="Discount" name="discount" id="discount_amount" class="form-control discount" onchange="discountAmount()">
                                        <input type="button" style="background:#dddbdb" id="discount-select-percentage" onclick="discountSelectPercentage()" class="form-control" value="%">
                                        <input type="button" id="discount-select-tk" onclick="discountSelectTk()" class="form-control" value="tk">
                                    </div>
                                    <div class="discount-code">

                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td class="total-line">Sub Total</td>
                                <td class="total-value text-right">
                                    <input type="number" placeholder="Sub Total" name="subtotal" id="sub_total" class="form-control subtotal" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="total-line">Paid</td>
                                <td class="total-value text-right ">
                                    <input type="number" onchange="paidAmount()" placeholder="Paid" name="paid" id="paid_total" class="form-control paid">
                                    <div id="paid-code"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="total-line">Due</td>
                                <td class="total-value text-right">
                                    <input type="number" placeholder="Due" name="due" id="due_total" class="form-control due" readonly>
                                </td>
                            </tr>
                        </table>




                        <!-- /.box-body -->

                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </form>
            <!-- /.box -->
        </div>
    </div>
</div>
@endsection
@push('scripts')
<style>
    .input-group-text {
        background: white;
        border: 1px solid #00000021 !important;
        border-radius: 0 5px 5px 0;
        height: 42px !important;
        font-size: 17px;
        width: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #00000075;
        border-left: none;
    }

    .total-line {
        text-align: end
    }

    #schedule_info,
    td,
    th {
        border: 1px solid #00000030 !important;
        padding: 10px 20px !important;
    }

    #schedule_info {
        margin-top: 22px !important;
        border-collapse: collapse !important;
    }

    #schedule_info {
        background: white;
    }

    #discount-section {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #discount-select-tk {
        width: 40px;
    }

    #discount-select-tk {
        width: 40px;
    }

    #discount-select-percentage {
        width: 40px;
    }

    .discount-code {
        text-align: start;
    }

    #paid-code {
        text-align: start;
    }

    ul#breadcrumb {
        padding: 10px 16px;
        background-color: #DD4B39;
        padding: 10px 50px;
    }

    ul#breadcrumb li {
        font-size: 13px;
        color: white;
    }

    .blur-radio-button {
        opacity: 0.6;
    }

</style>
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
                <label for="name">Second Mobile Number</label>
                <input class="form-control" placeholder="Enter Mobile Number" name="patient_phone_two" type="text">
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
            <div class="form-group">
                <label for="name">Date and Time</label>
                <input onclick="rating()" class="form-control datePicker_class start_date_and_time" placeholder="Enter Date and Time" id="start_date_and_time" name="start_date_and_time" type="text">
            </div>
            <div class="form-group">
                <label for="name">Address</label>
                <textarea class="form-control patient_address" placeholder="Enter Address" id="patient_address" name="patient_address" row="4"></textarea>
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
                <label for="name">Second Mobile Number</label>
                <input class="form-control" placeholder="Enter Mobile Number" name="patient_phone_two" type="text">
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
            <div class="form-group">
                <label for="name">Date and Time <span class="text-red"> * </span> </label>
                <input onclick="rating()" class="form-control datePicker_class start_date_and_time" placeholder="Enter Date and Time"  id="start_date_and_time"  name="start_date_and_time" type="text">
            </div>
            <div class="form-group">
                <label for="name">Address<span class="text-red"> * </span> </label>
                <textarea class="form-control patient_address" placeholder="Enter Address" id="patient_address" name="patient_address" row="4"></textarea>
            </div>
            `)

        ))
    })

    $('#vehicle_info_register_id').on('change', async function() {
        let vehicle_info_register_id = $(this).val()
        let driver_register_id = $('#driver_register_id').val()


        const response = await fetch('/member/vehicle_schedule_id/' + vehicle_info_register_id, {
            method: "GET"
        , });
        const result = await response.json();

        $('.schedule').empty()

        result.map(function(result, index) {
            if (vehicle_info_register_id == result.vehicle_info_id && driver_register_id == result.driver_id) {

                $('.schedule').append(`<input onchange="schedule(${result.id})" type="radio" class="mr-1 vehicle_schedule_id"  value="${result.id}" id="${result.id}" name="vehicle_schedule_id"><label for="${result.id}" class="mr-4"> ${result.start_time} - ${result.end_time} </label> </br>`)

            }
        })



    })

    async function schedule(e) {
        const response = await fetch('/member/vehicle_schedule_single_id/' + e, {
            method: "GET"
        , });
        const result = await response.json();

        $('#price_amount').empty().val(result.price)

        $('#sub_total').val('')
        $('.discount-code').empty()
        $('#paid-code').empty()
        $('#paid_total').val('')
        $('#due_total').val('')
        $('#discount_amount').val('')
    }

    function discountAmount() {
        const discount = $('#discount_amount').val()
        const total_value = $('#price_amount').val()

        if (discount != '') {

            if (discount < 100) {
                var dec = (discount / 100).toFixed(2);
                var mult = total_value * dec;
                var total = (total_value - mult);
                $('#sub_total').empty().val(Math.round(total))

                $('.discount-code').empty()
                $('#paid-code').empty()
                $('#paid_total').val('')
                $('#due_total').val('')
            } else if (discount > 100) {
                $('.discount-code').empty().append(`<code>Wrong Entry! Amount must be less than 100.</code>`)

                $('#sub_total').empty()
                $('#paid-code').empty()
                $('#paid_total').val('')
                $('#due_total').val('')
            } else {
                $('#sub_total').empty().val(0)

                $('.discount-code').empty()
                $('#paid-code').empty()
                $('#paid_total').val('')
                $('#due_total').val('')
            }
        }

    }

    function discountAmountTk() {
        const discount = $('#discount_amount').val()
        const total_value = $('#price_amount').val()

        const value = total_value - discount
        if (discount != '') {
            if (value > 0) {
                var total = (total_value - discount);
                $('#sub_total').empty().val(Math.round(total))

                $('.discount-code').empty()
                $('#paid-code').empty()
                $('#paid_total').val('')
                $('#due_total').val('')
            } else if (value < 0) {
                $('.discount-code').empty().append(`<code>Wrong Entry! Amount must be less than price.</code>`)

                $('#sub_total').val('')
                $('#paid-code').empty()
                $('#paid_total').val('')
                $('#due_total').val('')
            } else {
                $('#sub_total').empty().val(0)

                $('.discount-code').empty()
                $('#paid-code').empty()
                $('#paid_total').val('')
                $('#due_total').val('')
            }
        }



    }

    function paidAmount() {
        const sub_total = $('#sub_total').val()
        const paid = $('#paid_total').val()

        const value = sub_total - paid

        if (value < 0) {
            $('#paid-code').empty().append(`<code>Wrong Entry! Amount must be less than sub total.</code>`)
            $('#due_total').empty()
        } else if (value > 0) {
            var total = sub_total - paid;
            $('#due_total').val(Math.round(total))
            $('#paid-code').empty()
        } else {
            $('#due_total').val(0)
            $('#paid-code').empty()
        }

    }

    function discountSelectPercentage() {

        if ($('#discount-select-percentage').val() == '%') {
            $('#sub_total').val('')
            $('#paid-code').empty()
            $('#paid_total').val('')
            $('#due_total').val('')

            $('#discount-section').empty().html(`
                <input type="number"  placeholder="Discount" name="discount" id="discount_amount" class="form-control  discount" onchange="discountAmount()">
                <input type="button"  style="background:#dddbdb" id="discount-select-percentage" onclick="discountSelectPercentage()" class="form-control" value="%">
                <input type="button" id="discount-select-tk" onclick="discountSelectTk()" class="form-control" value="tk">
            `)
        }
    };


    function discountSelectTk() {
        if ($('#discount-select-tk').val() == 'tk') {
            $('#sub_total').val('')
            $('#paid-code').empty()
            $('#paid_total').val('')
            $('#due_total').val('')

            $('#discount-section').empty().html(`
                <input type="number"  placeholder="Discount" name="discount" id="discount_amount" class="form-control  discount" onchange="discountAmountTk()">
                <input type="button" id="discount-select-percentage" onclick="discountSelectPercentage()" class="form-control" value="%">
                <input type="button"  style="background:#dddbdb" id="discount-select-tk" onclick="discountSelectTk()" class="form-control" value="tk">
            `)
        }


    };

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


<script>
    $("#save_form").on("submit", function(e) {
        // e.preventDefault()
        const patient_name = $('#patient_name').val()
        const patient_phone_one = $('#patient_phone_one').val()
        const start_date_and_time = $('.start_date_and_time').val()
        const patient_address = $('.patient_address').val()
        const discount = $('.discount').val()
        const subtotal = $('.subtotal').val()
        const price = $('.price').val()
        const paid = $('.paid').val()
        const due = $('.due').val()
        const input_vehicle_info_register_id = $(".vehicle_info_register_id").val()
        const input_driver_register_id = $(".driver_register_id").val()
        const schedule = $("input[name='vehicle_schedule_id']:checked").val()

        $('#breadcrumb').empty()

        if (input_vehicle_info_register_id == "" || input_vehicle_info_register_id == null) {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Ambulance Name.</li>`)
            e.preventDefault()
        }


        if (input_driver_register_id == "" || input_driver_register_id == null) {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Driver Name.</li>`)
            e.preventDefault()
        }

        if (price == 'undefined') {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Price.</li>`)
            e.preventDefault()
        } else {
            if (price === "" || price == null) {
                $('#breadcrumb').css('display', 'block')
                $('#breadcrumb').append(`<li>Enter Price.</li>`)
                e.preventDefault()
            }
        }

        if (discount == 'undefined') {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Discount.</li>`)
            e.preventDefault()
        } else {
            if (discount === "" || discount == null) {
                $('#breadcrumb').css('display', 'block')
                $('#breadcrumb').append(`<li>Enter Discount.</li>`)
                e.preventDefault()
            }
        }


        if (subtotal == 'undefined') {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Subtotal Amount.</li>`)
            e.preventDefault()
        } else {
            if (subtotal === "" || subtotal == null) {
                $('#breadcrumb').css('display', 'block')
                $('#breadcrumb').append(`<li>Enter Subtotal Amount.</li>`)
                e.preventDefault()
            }
        }

        if (paid == 'undefined') {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Paid Amount.</li>`)
            e.preventDefault()
        } else {
            if (paid === "" || paid == null) {
                $('#breadcrumb').css('display', 'block')
                $('#breadcrumb').append(`<li>Enter Paid Amount.</li>`)
                e.preventDefault()
            }
        }

        if (due == 'undefined') {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Due Amount.</li>`)
            e.preventDefault()
        } else {
            if (due === "" || due == null) {
                $('#breadcrumb').css('display', 'block')
                $('#breadcrumb').append(`<li>Enter Due Amount.</li>`)
                e.preventDefault()
            }
        }


        if (patient_name == 'undefined') {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter patient Name.</li>`)
            e.preventDefault()
        } else {
            if (patient_name === "" || patient_name == null) {
                $('#breadcrumb').css('display', 'block')
                $('#breadcrumb').append(`<li>Enter Patient Name.</li>`)
                e.preventDefault()
            }
        }

        if (patient_phone_one == 'undefined') {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Patient Mobile.</li>`)
            e.preventDefault()
        } else {
            if (patient_phone_one === "" || patient_phone_one == null) {
                $('#breadcrumb').css('display', 'block')
                $('#breadcrumb').append(`<li>Enter Patient Mobile.</li>`)
                e.preventDefault()
            }
        }

        if (start_date_and_time == 'undefined') {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Start Date And Time.</li>`)
            e.preventDefault()
        } else {
            if (start_date_and_time === "" || start_date_and_time == null) {
                $('#breadcrumb').css('display', 'block')
                $('#breadcrumb').append(`<li>Enter Start Date And Time.</li>`)
                e.preventDefault()
            }
        }

        if (patient_address == 'undefined') {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Patient Address.</li>`)
            e.preventDefault()
        } else {
            if (patient_address === "" || patient_address == null) {
                $('#breadcrumb').css('display', 'block')
                $('#breadcrumb').append(`<li>Enter Patient Address.</li>`)
                e.preventDefault()
            }
        }

        if (schedule == 'undefined') {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Schedule.</li>`)
            e.preventDefault()
        } else {
            if (schedule === "" || schedule == null) {
                $('#breadcrumb').css('display', 'block')
                $('#breadcrumb').append(`<li>Enter Schedule.</li>`)
                e.preventDefault()
            }
        }


        if (patient_name !== "" && patient_phone_one !== "" && start_date_and_time !== "" && patient_address !== "" && schedule !== "") {
            return true
        }
    });

</script>
@endpush
