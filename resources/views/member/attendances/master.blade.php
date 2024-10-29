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
        'name' => 'Attendance Master',
//        'href' => $route,
    ],
//    [
//        'name' => 'Create',
//    ],
];

$data['data'] = [
    'name' => 'Attendance Master',
    'title'=>'Attendance Master',
    'heading' => 'Attendance Master',
];

?>



@extends('layouts.back-end.master', $data)


@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

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
                    <h3 class="box-title">Attendance Master</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.master-attendances','method' => 'get', 'role'=>'form' ]) !!}

                <div class="box-body">


                    <div class="col-md-3">

                        <div class="form-group">
                            <label for="status"> Employee  </label>
                            <select class="form-control select2" name="employee_id" id="employee_id" required>
                            <option value=""> Select Employee</option>
                                @foreach($employees as $value)
                                <option value="{{$value->employeeID}}"  > {{ $value->employeeID." - ".$value->first_name." ".$value->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name"> Year  </label>
                            <select class="form-control select2" name="year" id="year" required>
                                @foreach($years as $value)
                                    <option value="{{$value}}"  > {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name"> Month  </label>
                            <select class="form-control select2" name="month" id="month" required >
                                @foreach($months as $value)
                                    <option value="{{$value}}"  > {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    <div class="col-md-2 pt-3">
                        <button type="submit" class="btn btn-primary mt-3">View Report</button>
                    </div>

                </div>

                @if(count($attendances)>0)
                    <div class="box-header with-border">
                        <h3 class="box-title">{!! $report_title !!}  </h3>

                        <a href="{{  $full_url }}type=print" class="btn btn-sm btn-primary pull-right" id="btn-print"> <i class="fa fa-print"></i> Print </a>
                        <a href="{{  $full_url }}type=download" class="btn btn-sm btn-success pull-right mx-3"> <i class="fa fa-download"></i> Download </a>
                    </div>

                @endif

                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">
                            <table class="table table-responsive">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Date</th>
                                        <th>Shift</th>
                                        <th>In Time</th>
                                        <th>Out Time</th>
                                        <th>Late Time</th>
                                        <th>Attend Status</th>
                                        <th>Late Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total_present = 0;
                                    $total_absent = 0;
                                    $total_late = 0;
                                    $total_leave = 0;
                                @endphp
                                    @foreach($attendances as $key => $value)

                                        @php

                 /*   if($value->attend_status == 'P' && $value->shift_type == 0)
                    {
                    }else if($value->attend_status == 'P' && $value->shift_type == 1)
                    {
                    }else if($value->attend_status == 'P' && $value->shift_type_adv == 1)
                    {
                    }else if($value->attend_status == 'P' && $value->shift_type_adv == 0)
                    {
                    } */


                    if ($value->late == 'N'){
                        $value->late = '';
                    }
                     if ($value->attend_status == 'Present') {
                        //$value->late <= '00:00:00'
                        // $value->late = '';
//                        $value->attend_status = 'Present';
                        $total_present++;
                    }
                     if ($value->late <= '00:00:00') {

                        $value->late = '';
                    }

                    if ($value->attend_status == 'Weekend') {
//                      $value->intime = 'Weekend';
//                      $value->outtime = 'Weekend';
                      $value->late = '';
                    } else if ($value->attend_status == 'Absent') {
//                      $value->intime = 'Absent';
//                      $value->outtime = 'Absent';
//                      $value->late = 'Absent';
                      $total_absent++;
                    }else if ($value->attend_status == 'Leave') {
//                      $value->intime = 'Leave';
//                      $value->outtime = 'Leave';
//                      $value->late = 'Leave';
                      $total_leave++;
                    }else if ($value->attend_status == 'Holiday') {
//                      $value->intime = 'Holiday';
//                      $value->outtime = 'Holiday';
//                      $value->late = 'Holiday';
                    }

                @endphp

                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->employeeID }}</td>
                    <td>{{ $value->UNAME }}</td>
                    <td>{{ date('M d, Y', strtotime($value->attend_date ))}}</td>
                    <td>{{ $value->shift }}</td>
                    <td>{{ $value->intime }}</td>
                    <td>{{ $value->outtime }}</td>
                    <td>{{ $value->late }}</td>
                    <td>{{ $value->attend_status }}</td>
                    <td>{{ $value->late_status }}</td>
                </tr>
                                    @endforeach


                                @if(count($attendances)>0)
                                <tr>
                                    <th> Total Working Days</th>
                                    <th> {{ $workingDays }}</th>
                                    <th> Total Present</th>
                                    <th> {{ $total_present }}</th>
                                    <th> Total Absent</th>
                                    <th> {{ $total_absent }}</th>
                                    <th> Total Leave</th>
                                    <th> {{ $total_leave }}</th>
                                    <th> Total Late</th>
                                    <th> {{ $total_late }}</th>
                                </tr>
                                    @endif
                                </tbody>
                            </table>
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
    <script type="text/javascript">

        $(function () {

            $('#start_date').datepicker({
                "format": 'yyyy-mm-dd',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });

            $('.select2').select2();

            $('#myTable').DataTable();
        });

    </script>

@endpush

