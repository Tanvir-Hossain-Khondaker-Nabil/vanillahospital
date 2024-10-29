<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/1/2019
 * Time: 11:56 AM
 */


 $route =  \Auth::user()->can(['member.sale_commission_paid.index']) ? route('member.sale_commission_paid.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";
 $data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Sale Commission Paid',
        'href' =>  $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Sale Commission Paid',
    'title'=>'Sale Commission Paid',
    'heading' => 'Sale Commission Paid',
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


                {!! Form::open(['route' => 'member.sale_commission_paid.store', 'method' => 'POST', 'role'=>'form' ]) !!}


                <div class="box-body ">
                    <div class="col-md-6 mt-5">
                        <div class="form-group">
                                <label>  Select Employee </label>
                                {!! Form::select('employee_id', $employees, null,['id'=>'employee_id','class'=>'form-control select2','placeholder'=>'Select Employee']); !!}
                        </div>

                        <div class="form-group">
                            <label>  Paid Amount </label>

                            {!! Form::hidden('due_amount',null,['id'=>'due_amount']); !!}
                            {!! Form::number('paid_amount',null,['id'=> 'paid_amount', 'class'=>'form-control','placeholder'=>'Enter paid amount', 'required', 'step'=>"any"]); !!}
                        </div>
                    </div>
                    <div class="col-md-6 mt-5">
                        <div class="form-group mb-1 pt-4">
                                <label class="font-weight-bold" style="width: 210px;">  Employee Name</label> :
                                <label id="employee_name">  </label>


                        </div>
                        <div class="form-group mb-1">
                                <label class="font-weight-bold" style="width: 210px;">  Employee ID</label> :
                                <label id="employeeId">  </label>


                        </div>
                        <div class="form-group mb-1">
                                <label class="font-weight-bold" style="width: 210px;">  Employee Phone</label> :
                                <label id="employee_phone">  </label>


                        </div>
                        <div class="form-group mb-1">
                                <label class="font-weight-bold" style="width: 210px;">  Total Commission Amount</label> :
                                <label id="commission_amount">  </label>


                        </div>
                        <div class="form-group mb-1">
                            <label class="font-weight-bold" style="width: 210px;">  Total Paid</label> :
                            <label id="total_paid">  </label>
                        </div>
                        <div class="form-group mb-1" >
                            <label class="font-weight-bold" style="width: 210px;">  Total Due</label> :
                            <label id="total_due" class="text-danger">  </label>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-success form-group"> Paid Commission </button>
                    </div>
                </div>
                <!-- /.box-body -->
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')

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
