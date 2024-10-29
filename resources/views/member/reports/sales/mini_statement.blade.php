<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/6/2021
 * Time: 12:58 PM
 */



$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('member.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Reports',
        'href' => "#",
    ],
    [
        'name' => "Sales Mini Statement",
    ],
];

$data['data'] = [
    'name' => "Sales Mini Statement",
    'title'=> "Sales Mini Statement",
    'heading' => "Sales Mini Statement",
];

?>
@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Search</h3>
                </div>

            {!! Form::open(['route' => Route::current()->getName(),'method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <label>  Select User </label>
                            {!! Form::select('manager_id', $managers, null,['id'=>'manager_id','class'=>'form-control select2','placeholder'=>'Select Manager']); !!}
                        </div>
                        <div class="col-md-3">
                            <label>  Shift </label>
                            {!! Form::select('shift_id', $shifts, null,['id'=>'shift_id','class'=>'form-control select2','placeholder'=>'Select Shift']); !!}
                        </div>

                        <div class="col-md-3">
                            <label> Date </label>
                            <input class="form-control date" name="from_date" required value="" autocomplete="off"/>
                        </div>

                        <div class="col-md-3 margin-top-23">
                            <label></label>
                            <input class="btn btn-info" value="Search" type="submit"/>
                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>

                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                {{--<div class="box-body">--}}

                {{----}}
                {{--</div>--}}

                {!! Form::close() !!}
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{!! $report_title !!} </h3>

                    @include('member.reports.print_title_btn')
                </div>

                <div class="box-body">

                    <div class="col-lg-8">
                        <table class="table table-striped" id="dataTable" >

                            <tbody>
                            <tr>
                                <th>Total Sale</th>
                                <th colspan="5" class="text-right">{{ create_money_format($total_sale) }}</th>
                            </tr>
                            <tr>
                                <th>Cash Sale</th>
                                <th  colspan="5" class="text-right">{{ create_money_format($total_paid) }}</th>
                            </tr>
                            <tr>
                                <th>Due Sale</th>
                                <th colspan="5"  class="text-right">{{ create_money_format($total_due) }}</th>
                            </tr>
                            <tr>
                                <th class="text-left" style="padding-left:80px; border-top: 1px solid #E0E2E4;">
                                   Customer Name
                                </th>
                                <th class="text-left" style="border-top: 1px solid #E0E2E4;">Product Name</th>
                                <th class="text-right" style="border-top: 1px solid #E0E2E4;">Total Qty</th>
                                <th class="text-right" style="border-top: 1px solid #E0E2E4;">Unit</th>
                                <th class="text-right" style="border-top: 1px solid #E0E2E4;">Total Price</th>
{{--                                <th class="text-right" style="border-top: 1px solid #E0E2E4;">Due</th>--}}
                            </tr>
                            @php
                                $customer_id =  null;
                                $sale_id =  null;
                                $total = $due_total =  0;
                                $rowspan =  0;
                            @endphp
                            @foreach($sales as $value)

                                @php
                                    $customer_name =  $value->customer ? $value->customer->name_phone : '';
                                    $due = ($value->total_due/$value->count);
                                @endphp
                                <tr>
                                    <td style="padding-left:80px; border-top: 1px solid #E0E2E4;">
                                        @if($customer_id != $value->customer_id)
                                            {{ $customer_name }}
                                        @endif
                                    </td>
                                    <td class="text-left" style="border-top: 1px solid #E0E2E4;">{{ $value->item_name }}</td>
                                    <td class="text-right" style="border-top: 1px solid #E0E2E4;">{{ $value->total_qty }} </td>
                                    <td class="text-right" style="border-top: 1px solid #E0E2E4;">{{ $value->unit }}</td>
                                    <td class="text-right" style="border-top: 1px solid #E0E2E4;">{{ create_money_format($value->total_price) }}</td>
{{--                                    <td class="text-right" style="border-top: 1px solid #E0E2E4;">--}}
{{--                                            {{ create_money_format($due) }}--}}
{{--                                    </td>--}}
                                </tr>
                                @php
                                    $customer_id =  $value->customer_id;
                                    $sale_id =  $value->id;
                                    $total +=  $value->total_price;
                                    $due_total = $due_total+$due;
                                @endphp
                            @endforeach

                                <tr>
                                    <th colspan="4"  >Total</th>
                                    <th class="text-right">{{ create_money_format($total) }}</th>
{{--                                    <th class="text-right">{{ create_money_format($due_total) }}</th>--}}
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection



@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready( function(){
            $('.select2').select2();
            $('.date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });
        });
    </script>
@endpush
