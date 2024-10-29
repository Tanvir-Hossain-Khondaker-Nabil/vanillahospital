<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route =  \Auth::user()->can(['member.attendances.index']) ? route('member.attendances.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Employee Attendance',
//        'href' => $route,
    ],
//    [
//        'name' => 'Create',
//    ],
];

$data['data'] = [
    'name' => 'Employee Attendance',
    'title'=>'Manual Employee Attendance',
    'heading' => 'Manual Employee Attendance',
];

?>



@extends('layouts.back-end.master', $data)


@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.css" integrity="sha512-BB0bszal4NXOgRP9MYCyVA0NNK2k1Rhr+8klY17rj4OhwTmqdPUQibKUDeHesYtXl7Ma2+tqC6c7FzYuHhw94g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush

@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

        @include('common._alert')

        @include('common._error')
        <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Manual Employee Attendance</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.attendances.store','method' => 'POST', 'role'=>'form' ]) !!}

                <div class="box-body">


                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="status"> Employee  <span class="text-red"> * </span> </label>
                            <select class="form-control select2" required name="employee_id" id="employee_id" >
                                <option value=""> Select Employee</option>
                                @foreach($employees as $value)
                                    <option value="{{$value->id}}"  > {{ $value->employeeID." - ".$value->first_name." ".$value->last_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="name"> Date <span class="text-red"> * </span> </label>
                                {!! Form::text('start_date', null,['id'=>'start_date','class'=>'form-control ','placeholder'=>'Enter start date', 'required', 'autocomplete'=>"off"]); !!}
                        </div>

                        <div class="form-group">
                            <label for="display_name">Time In<span class="text-red"> * </span> </label>
                            {!! Form::text('time_in',null,['id'=>'time_in','class'=>'form-control input-group clockpicker', 'data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>'Enter time in']); !!}
                        </div>
                        <div class="form-group">
                            <label for="display_name">Time Out<span class="text-red"> * </span> </label>
                            {!! Form::text('time_out',null,['id'=>'time_out','class'=>'form-control input-group clockpicker', 'data-placement'=>'right','data-align'=>'top','data-autoclose'=>'true','placeholder'=>'Enter time out']); !!}
                        </div>

                    </div>

                    <div class="box-footer">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>
        </div>
    </div>
@endsection


@push('scripts')

    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- CK Editor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

    <!-- Date range picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/jquery-clockpicker.js" integrity="sha512-1QoWYDbO//G0JPa2VnQ3WrXtcgOGGCtdpt5y9riMW4NCCRBKQ4bs/XSKJAUSLIIcHmvUdKCXmQGxh37CQ8rtZQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">

        $('.clockpicker').clockpicker({
                twelvehour: true,
                donetext: "Done",
                autoclose: false,
            })
            .find('input').change(function(){

            });

        $(function () {

            $('#start_date').datepicker({
                "format": 'yyyy-mm-dd',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });

            $('.select2').select2();
        });

    </script>

@endpush

