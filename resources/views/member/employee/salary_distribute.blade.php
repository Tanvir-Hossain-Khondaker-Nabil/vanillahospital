<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/1/2019
 * Time: 11:56 AM
 */


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('member.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Employees',
        'href' => route('member.employee.index'),
    ],
    [
        'name' => 'Salary Distribute',
    ],
];

$data['data'] = [
    'name' => 'Salary Distribute',
    'title'=>'Salary Distribute',
    'heading' => 'Salary Distribute',
];

?>
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._error')

            @include('common._alert')

            <div class="box">


                {!! Form::open(['route' => 'member.employee_salary_paid.store', 'method' => 'POST', 'role'=>'form' ]) !!}


                <div class="box-body ">
                    <div class="col-md-6 mt-5">

                        <div class="form-group">
                            <label>  Select Employee </label>
                            {!! Form::select('employee_id', $employees, $emp_salary ? $emp_salary->emp_id : null,['id'=>'employee_id','class'=>'form-control select2','placeholder'=>'Select Employee', $emp_salary ? 'readonly' : '']); !!}
                        </div>

                        <div class="form-group">
                            <label>  Salary Amount </label>

                            {!! Form::hidden('salary_id', $emp_salary ? $emp_salary->id : null,['id'=>'salary_id']); !!}
                            {!! Form::hidden('due_amount',null,['id'=>'due_amount']); !!}
                            {!! Form::number('paid_amount',$emp_salary ? create_float_format($emp_salary->net_payable) : null,['id'=> 'paid_amount', 'class'=>'form-control','placeholder'=>'Enter salary amount', 'required', 'step'=>"any"]); !!}

                        </div>

                        @if($emp_salary)

                            <div class="form-group">
                                <label> Salary Month </label>
                                {!! Form::text('month', $emp_salary->en_month.", ".$emp_salary->en_year ,['id'=> 'month', 'class'=>'form-control','placeholder'=>'Enter month', 'disabled']); !!}

                            </div>

                        @endif

                        <div class="form-group">
                            <label> Given Date </label>
                            {!! Form::text('given_date',null,['id'=> 'date', 'class'=>'form-control','placeholder'=>'Enter given date', 'required', 'autocomplete'=>"off"]); !!}

                        </div>
                    </div>
                    <div class="col-md-6 mt-5">
                        <div class="form-group mb-1 pt-4">
                            <label class="font-weight-bold" style="width: 210px;">  Employee Name</label> :
                            <label id="employee_name"> {{ $emp_salary ? $emp_salary->employee->uc_full_name : null }} </label>

                        </div>
                        <div class="form-group mb-1">
                            <label class="font-weight-bold" style="width: 210px;">  Employee ID</label> :
                            <label id="employeeId">  {{ $emp_salary ? $emp_salary->employee->employeeID : null }}  </label>

                        </div>
                        <div class="form-group mb-1">
                            <label class="font-weight-bold" style="width: 210px;">  Employee Phone</label> :
                            <label id="employee_phone">    {{ $emp_salary ? $emp_salary->employee->phone2 : null }}   </label>
                        </div>
                        <div class="form-group mb-1">
                            <label class="font-weight-bold" style="width: 210px;">  Bank</label> :
                            <label id="employee_phone">    {{ $emp_salary ? $emp_salary->employee->bank->display_name : null }}   </label>
                        </div>
                        <div class="form-group mb-1">
                            <label class="font-weight-bold" style="width: 210px;">  Branch</label> :
                            <label id="employee_phone">    {{ $emp_salary ? $emp_salary->employee->bank_branch->branch_name : null }}   </label>
                        </div>
                        <div class="form-group mb-1">
                            <label class="font-weight-bold" style="width: 210px;">  Account Number</label> :
                            <label id="employee_phone">    {{ $emp_salary ? $emp_salary->employee->bank_account : null }}   </label>
                        </div>
                        {{--<div class="form-group mb-1">--}}
                            {{--<label class="font-weight-bold" style="width: 210px;">  Total Commission Amount</label> :--}}
                            {{--<label id="commission_amount">  </label>--}}
                        {{--</div>--}}
                        {{--<div class="form-group mb-1">--}}
                            {{--<label class="font-weight-bold" style="width: 210px;">  Total Paid</label> :--}}
                            {{--<label id="total_paid">  </label>--}}
                        {{--</div>--}}
                        {{--<div class="form-group mb-1" >--}}
                            {{--<label class="font-weight-bold" style="width: 210px;">  Total Due</label> :--}}
                            {{--<label id="total_due" class="text-danger">  </label>--}}
                        {{--</div>--}}
                    </div>
                </div>

                @if( $emp_salary == "" || ($emp_salary != "" && $emp_salary->given_status == 0))
                <div class="box-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success form-group"> Paid Salary </button>
                    </div>
                </div>
                @endif
                <!-- /.box-body -->
                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <!-- Date range picker -->
    <script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready( function(){
            $('.select2').select2();

            $('#date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });
            var today = moment().format('MM\DD\YYYY');
            $('#date').datepicker('setDate', today);
            $('#date').datepicker('update');


            $("#employee_id").on('change',function(e){
                e.preventDefault();
                var url = "{{ route('member.search.employee_details') }}";
                var id = $(this).val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'employee_id' : id
                };
                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function( data) {

                    if(data.status == "success")
                    {
                        var employee = data.employee;
                        var sale_commission = parseFloat(data.sale_commission).toFixed(2);
                        var paid_commission = parseFloat(data.paid_commission).toFixed(2);
                        var due =  parseFloat(data.sale_commission-data.paid_commission).toFixed(2);

                        $('#employee_name').html(employee.uc_full_name);
                        $('#employee_phone').html(employee.phone2);
                        $('#employeeId').html(employee.employeeID);
                        $('#commission_amount').html(sale_commission);
                        $('#total_paid').html(paid_commission);
                        $('#total_due').html(due);
                        $('#due_amount').val(due);
                    }else{
                        bootbox.alert("No data Found!! ");
                    }
                });
            });
        });
    </script>
@endpush
