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
        'name' => 'Edit',
    ],
];

$data['data'] = [
    'name' => 'Vehicle Detail',
    'title'=> 'Edit Ambulance Booking',
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
                <h3 class="box-title">Edit Ambulance Booking</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            <form method="POST" id="save_form" action="{{route('member.vehicle_detail.update',$vehicleDetail->id)}}">
                @csrf
                @if(isset($vehicleDetail))
                @method('put')
                @endif
                <div class="box-body">
                    <input type="hidden" value="{{@$vehicleDetail->invoice_number}}" name="invoice_number">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Driver Name<span class="text-red"> * </span> </label>
                            <select id="driver_register_id" class="form-control driver_register_id" name="driver_id">
                                <option value="{{$vehicleDetail->driver_id}}">{{$vehicleDetail->driver->name}}</option>
                                <option>Select</option>
                                @foreach($drivers as $key=>$driver)
                                <option value="{{$key}}">{{$driver}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Ambulance Name<span class="text-red"> * </span> </label>
                            <select id="vehicle_info_register_id" class="form-control vehicle_info_register_id" name="vehicle_info_id">
                                <option value="{{$vehicleDetail->vehicle_info_id}}">{{$vehicleDetail->vehicleInfo->model_no}}</option>
                                <option>Select</option>

                                @foreach($vehicleInfos as $key=>$vehicle_info)
                                <option value="{{$key}}">{{$vehicle_info}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name">Ambulance Schedule<span class="text-red"> * </span> </label><br>
                            @foreach($vehicleSchedules as $key=>$vehicle_Schedule)
                            <input type="radio" onchange="schedule({{$vehicle_Schedule->id}})" {{ $vehicleDetail->vehicle_schedule_id == $vehicle_Schedule->id ? 'checked' : ''}} class="mr-1" id="{{$vehicle_Schedule->id}}" value="{{$vehicle_Schedule->id}}" name="vehicle_schedule_id">
                            <label for="{{$vehicle_Schedule->id}}" class="mr-4"> ({{$vehicle_Schedule->start_time}} - {{$vehicle_Schedule->end_time}})</label> <br>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <label for="name">Name<span class="text-red"> * </span> </label>
                            <input class="form-control" placeholder="Enter Name" value="{{@$vehicleDetail->patient_name}}" id="patient_name" name="patient_name" type="text">
                        </div>
                        <div class="form-group">
                            <label for="name">Email <span class="text-red"> * </span> </label>
                            <input class="form-control" placeholder="Enter Email" value="{{@$vehicleDetail->patient_email}}" id="patient_email" name="patient_email" type="email">
                        </div>
                        <div class="form-group">
                            <label for="name">First Mobile Number <span class="text-red"> * </span> </label>
                            <input class="form-control" placeholder="Enter Phone" value="{{@$vehicleDetail->patient_phone_one}}" id="patient_phone_one" name="patient_phone_one" type="text">
                        </div>

                        <div class="form-group">
                            <label for="name">Second Mobile Number <span class="text-red"> * </span> </label>
                            <input class="form-control" placeholder="Enter Phone" value="{{@$vehicleDetail->patient_phone_two}}" name="patient_phone_two" type="text">
                        </div>
                        <div class="form-group">
                            <label for="name">Date and Time <span class="text-red"> * </span> </label>
                            <input class="form-control datePicker" placeholder="Enter Date and Time" value="{{@$vehicleDetail->start_date_and_time}}" id="start_date_and_time" name="start_date_and_time" type="text">
                        </div>
                        <div class="form-group">
                            <label for="name">Age <span class="text-red"> * </span> </label>
                            <input class="form-control" placeholder="Enter Age" value="{{@$vehicleDetail->age}}" id="age" name="age" type="text">
                        </div>

                        <div class="form-group">
                            <label for="name">Gender <span class="text-red"> * </span> </label>
                            <input class="form-control" placeholder="Enter Age" value="{{@$vehicleDetail->gender}}" id="gender" name="gender" type="text">
                        </div>

                        <div class="form-group">
                            <label for="name">Date of Birth <span class="text-red"> * </span> </label>
                            <input class="form-control datePicker_class"  onclick="rating()" placeholder="Enter Date of Birth" value="{{@$vehicleDetail->date_of_birth}}" id="date_of_birth" name="date_of_birth" type="text">
                        </div>

                        <div class="form-group">
                            <label for="name">Blood Group <span class="text-red"> * </span> </label>
                            <input class="form-control" placeholder="Enter blood_group" value="{{@$vehicleDetail->blood_group}}" id="blood_group" name="blood_group" type="text">
                        </div>
                        <div class="form-group">
                            <label for="name">Address<span class="text-red"> * </span> </label>
                            <textarea class="form-control" placeholder="Enter Address" id="patient_address" name="patient_address" row="4">{{@$vehicleDetail->patient_address}}</textarea>
                        </div>


                    </div>
                    <div class="col-md-6">
                        <table style="width: 100%" class="sales_table_2">
                            <tr>
                                <td class="total-line">Price</td>
                                <td class="total-value text-right">
                                    <input type="text" class="form-control price" value="{{$vehicleDetail->vehicleSchedule->price}}" placeholder="Price" id="price_amount" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="total-line">Discount</td>
                                <td class="total-value text-right">
                                    <div id="discount-section">
                                        <input type="number" placeholder="Discount" name="discount" id="discount_amount" value="{{@$vehicleDetail->discount}}" class="form-control discount" onchange="discountAmount()">
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
                                    <input type="number" placeholder="Sub Total" value="{{@$vehicleDetail->subtotal}}" name="subtotal" id="sub_total" class="form-control subtotal" readonly>
                                </td>
                            </tr>
                            <tr>
                                <td class="total-line">Paid</td>
                                <td class="total-value text-right ">
                                    <input type="number" onchange="paidAmount()" value="{{@$vehicleDetail->paid}}" placeholder="Paid" name="paid" id="paid_total" class="form-control paid">
                                    <div id="paid-code"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="total-line">Due</td>
                                <td class="total-value text-right">
                                    <input type="number" placeholder="Due" value="{{@$vehicleDetail->due}}" name="due" id="due_total" class="form-control due" readonly>
                                </td>
                            </tr>
                        </table>




                        <!-- /.box-body -->

                    </div>

                </div>

                <div class="box-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">Update</button>
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
<style>
    ul#breadcrumb {
        padding: 10px 16px;
        background-color: #DD4B39;
        padding: 10px 50px;
    }

    ul#breadcrumb li {
        font-size: 13px;
        color: white;
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

</style>
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

        if (discount != '') {}

        if (discount < 100) {
            var dec = (discount / 100).toFixed(2);
            var mult = total_value * dec;
            var total = (total_value - mult);
            $('#sub_total').empty().val(total)

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

    function discountAmountTk() {
        const discount = $('#discount_amount').val()
        const total_value = $('#price_amount').val()

        const value = total_value - discount

        if (value > 0) {
            var total = (total_value - discount);
            $('#sub_total').empty().val(total)

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

    function paidAmount() {
        const sub_total = $('#sub_total').val()
        const paid = $('#paid_total').val()

        const value = sub_total - paid

        if (value < 0) {
            $('#paid-code').empty().append(`<code>Wrong Entry! Amount must be less than sub total.</code>`)
            $('#due_total').empty()
        } else if (value > 0) {
            var total = sub_total - paid;
            $('#due_total').val(total)
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
    $("#save_form").on("click", function(e) {
        // e.preventDefault()
        const patient_name = $('#patient_name').val()
        const patient_phone_one = $('#patient_phone_one').val()
        const start_date_and_time = $('#start_date_and_time').val()
        const patient_address = $('#patient_address').val()
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

        if (price == "" || price == null) {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Price.</li>`)
            e.preventDefault()
        }


        if (discount == "" || discount == null) {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Discount.</li>`)
            e.preventDefault()
        }


        if (subtotal == "" || subtotal == null) {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Subtotal Amount.</li>`)
            e.preventDefault()
        }

        if (paid == "" || paid == null) {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Paid Amount.</li>`)
            e.preventDefault()
        }

        if (due == "" || due == null) {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Due Amount.</li>`)
            e.preventDefault()
        }


        if (patient_name == "" || patient_name == null) {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Patient Name.</li>`)
            e.preventDefault()
        }


        if (patient_phone_one == "" || patient_phone_one == null) {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Patient Mobile.</li>`)
            e.preventDefault()
        }


        if (start_date_and_time == "" || start_date_and_time == null) {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Start Date And Time.</li>`)
            e.preventDefault()
        }


        if (patient_address == "" || patient_address == null) {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Patient Address.</li>`)
            e.preventDefault()
        }


        if (schedule == "" || schedule == null) {
            $('#breadcrumb').css('display', 'block')
            $('#breadcrumb').append(`<li>Enter Schedule.</li>`)
            e.preventDefault()
        }

        if (patient_name !== "" && patient_phone_one !== "" && start_date_and_time !== "" && patient_address !== "" && schedule !== "") {
            return true
        }
    });

</script>
@endpush
