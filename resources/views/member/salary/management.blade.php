<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

 $route = \Auth::user()->can(['member.salary_management.index']) ?route('member.salary_management.index'): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Salary Management',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Salary Management',
    'title'=>'Salary Management',
    'heading' => 'Salary Management',
];

?>



@extends('layouts.back-end.master', $data)


@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

    <style type="text/css">
        #myTable td{
            vertical-align: middle;
        }
    </style>
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
                    <h3 class="box-title">Salary Management</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.salary_management.index','method' => 'get', 'role'=>'form' ]) !!}

                <div class="box-body">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name"> Year  </label>
                            <select class="form-control select2" name="year" id="year" required>
                                <option value=""> Select Year</option>
                                @foreach($years as $value)
                                    <option value="{{$value}}" {{ $value == $year ? "selected" : ''}} > {{ $value }}</option>
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
                                    <option value="{{$value}}"  {{ $value == $month ? "selected" : ''}}  > {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2 pt-3">
                        <button type="submit" class="btn btn-primary mt-3">Salary Generate</button>
                    </div>

                </div>

                {{--@include('member.reports.print_title_btn')--}}

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
                                    <th>Designation</th>
                                    <th>Gross Salary (&euro;)</th>
                                    <th>Salary System</th>
                                    <th>W/D</th>
                                    <th>Absent</th>
                                    <th>Present</th>
                                    <th>E/W</th>
                                    <th>Total W/D</th>
                                    <th>Total as per Attendance (&euro;)</th>
                                    <th class="hidden">PerDay</th>
                                    <th>Adv Realisation (&euro;)</th>
                                    <th>Bonus  (&euro;)</th>
                                    <th>Tax ({{ auth()->user()->company->tax }}%)</th>
                                    <th>Net Payable (&euro;)</th>
                                    <th>Receive Amount (&euro;)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($salaries as $key => $value)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value->employee->employeeID }}</td>
                                        <td>{{ $value->employee->uc_full_name }}</td>
                                        <td>{{ $value->emp_designation }}</td>
                                        <td class="text-center">{{ create_money_format($value->base_salary) }}</td>
                                        <td>{{ $value->salary_system }}</td>
                                        <td>{{ $working_days }}</td>
                                        <td>{{ $value->total_absent }}</td>
                                        <td>{{ $value->total_present }}</td>
                                        <td>{{ $value->extra_work }}</td>
                                        <td>{{ $value->total_work_day }}</td>
                                        <td>{{ create_money_format( $value->total_att_amount) }}</td>
                                        <td>{{  create_money_format($value->advance_payment) }}</td>
                                        <td>{{  create_money_format($value->festival_bonus) }}</td>
                                        <td>{{  create_money_format($value->final_tax_amount) }}</td>
                                        <td>{{ create_money_format($value->net_payable) }}</td>
                                        <td>{{ create_money_format($value->receive_amount) }}</td>
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

