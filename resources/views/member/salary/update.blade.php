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
        'name' => 'Salary Update',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Salary Update',
    'title'=>'Salary Update',
    'heading' => 'Salary Update',
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
                    <h3 class="box-title">Salary Update</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::open(['route' => 'member.salary_management.create','method' => 'get', 'role'=>'form' ]) !!}

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
                        <button type="submit" class="btn btn-primary mt-3">Salary Report</button>
                    </div>

                </div>

                @include('member.reports.print_title_btn')

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
                                    <th>Salary System</th>
                                    <th>Gross Salary (&euro;)</th>
                                    <th>W/D</th>
                                    <th>Absent</th>
                                    <th>Present</th>
                                    <th>E/W</th>
                                    <th>P/D</th>
                                    <th>Total W/D</th>
                                    <th>Total as per Attendance (&euro;)</th>
                                    {{--<th>PerDay</th>--}}
                                    <th>Adv Realisation (&euro;)</th>
                                    <th>Bonus  (&euro;)</th>
                                    <th>Tax ({{ auth()->user()->company->tax }}%)</th>
                                    <th>Net Payable (&euro;)</th>
                                    <th>Receive Amount (&euro;)</th>
                                    <th>Paid Status</th>
                                    <th>Pay Slip</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($salaries as $key => $value)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value->employee->employeeID }}</td>
                                        <td>{{ $value->employee->uc_full_name }}</td>
                                        <td>{{ $value->emp_designation }}</td>
                                        <td class="text-center">
                                            {{ create_money_format($value->base_salary) }}
                                        </td>
                                        <td>{{ $value->salary_system }}</td>
                                        <td>{{ $working_days }}</td>
                                        <td>{{ $value->total_absent }}</td>
                                        <td>{{ $value->total_present }}</td>
                                        <td>
                                            <input class='change-pay form-control w-100' data-target="{{$value->id}}" id="extra_work{{$value->id}}"  {{ $value->given_status  ? "readonly" : '' }} type='number' value='{{ $value->extra_work }}'>

                                        </td>
                                        <td>
                                            <input class='change-pay form-control w-100' type='number' min='0' id="p_day{{$value->id}}" value='{{ $value->p_day }}'  {{ $value->given_status  ? "readonly" : '' }}  data-target="{{$value->id}}" >

                                        </td>
                                        <td>{{ $value->total_work_day }}</td>
                                        <td>{{ create_money_format( $value->total_att_amount) }}</td>
                                        <td  id="advance{{$value->id}}">
                                            {{  create_money_format($value->advance_payment) }}
                                            <input min='0' class='change-pay form-control' type='hidden' id="advance{{$value->id}}" value=''  {{ $value->given_status  ? "readonly" : '' }}  data-target="{{$value->id}}">
                                        </td>
                                        <td >
                                            <input min='0' class='change-pay form-control' style="width: 80px !important;" type='number' value='{{$value->festival_bonus }}' data-target="{{$value->id}}"  id="bonus{{$value->id}}" {{ $value->given_status  ? "readonly" : '' }}>
                                        </td>
                                        <td>{{  create_money_format($value->final_tax_amount) }}</td>
                                        <td id="net_pay_{{$value->id}}">{{ create_money_format($value->net_payable) }}</td>
                                        <td>{{ create_money_format($value->receive_amount) }}</td>
                                        <td>

                                            @if($value->given_status == 0)
{{--                                            <input class='paid_status' data-target="{{$value->id}}" value='{{$value->sign}}' type='checkbox' {{$value->sign == 1? "checked='checked'" : ''}}/>--}}
                                                <span class="label label-default"> Unpaid</span>
                                            @else
                                                <span class="label label-success">Paid</span>
                                            @endif
                                        </td>

                                        <td>
                                        <a href='{{ route('member.salary_management.show', $value->id) }}{{ str_replace(url()->current(), "", url()->full()) }}&type=print' class='btn btn-success btn-sm'  id="btn-print"> <i class="fa fa-print"></i> Pay Slip</a>

                                            @if($value->given_status == 0)
                                        <a href='{{ route('member.employee_salary_paid') }}?salary_id={{ $value->id}}' class='btn btn-primary btn-sm mt-2'> <i class="fa fa-paper-plane-o"></i> Pay Salary</a>
                                            @endif
                                        </td>
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
            $('#myTable').DataTable( {
                "pageLength": 25
            });

            $(".paid_status").change(function (e) {
                e.preventDefault();

                if($(this).is(":checked")){
                    var paid = 1;
                }else{
                    var paid = 0;
                }

                var salary_id = $(this).data('target');
                var url = "{{ route('member.salary_management.emp_paid_status') }}";

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'salary_id' : salary_id,
                    'paid' : paid,
                };

                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function(data) {

                    if(data.status=='success'){

                        bootbox.alert("Paid Status Changed Successfully");
                    }else{
                        bootbox.alert("Unable to update");
                    }
                })
            });

            $(".change-pay").change(function (e) {

                var salary_id = $(this).data('target');
                var bonus = $("#bonus"+salary_id).val();
                var advance = $("#advance"+salary_id).val();
                var p_day = $("#p_day"+salary_id).val();
                var extra_work = $("#extra_work"+salary_id).val();

                var url = "{{ route('member.salary_management.emp_update_salary') }}";

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'salary_id' : salary_id,
                    'advance' : advance,
                    'p_day' : p_day,
                    'bonus' : bonus,
                    'extra_work' : extra_work
                };

                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function(data) {

                    if(data.status=='success'){
                        $("#net_pay_"+salary_id).text(data.net_payable);
                    }else{
                        bootbox.alert("Unable to update");
                    }
                })
            });
        });

    </script>

@endpush

