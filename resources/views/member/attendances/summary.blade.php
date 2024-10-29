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
        'name' => 'Attendance Summary',
//        'href' => $route,
    ],
//    [
//        'name' => 'Create',
//    ],
];

$data['data'] = [
    'name' => 'Attendance Summary',
    'title'=>'Attendance Summary',
    'heading' => 'Attendance Summary',
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
                    <h3 class="box-title">Attendance Summary</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.summary-attendances','method' => 'get', 'role'=>'form' ]) !!}

                <div class="box-body">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name"> Year  </label>
                            <select class="form-control select2" name="year" id="year" required>
                                <option value=""> Select Year</option>
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
                                <option value=""> Select Month</option>
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
                            <table class="table table-responsive" id="myTable">
                                <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Present</th>
                                    <th>Extra Day</th>
                                    <th>Absent</th>
                                    <th>Weekend</th>
                                    <th>Holiday</th>
                                    <th>Leave</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($attendances as $key => $value)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value->employeeID }}</td>
                                        <td>{{ $value->UNAME }}</td>
                                        <td>{{ $value->presentday }}</td>
                                        <td>{{ $value->extraday }}</td>
                                        <td>{{ $value->absentday }}</td>
                                        <td>{{ $value->Weekend }}</td>
                                        <td>{{ $value->Holiday }}</td>
                                        <td>{{ $value->leaveday }}</td>
                                    </tr>
                                @endforeach
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

