<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/18/2019
 * Time: 3:32 PM
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
        'name' => $report_title,
    ],
];

$data['data'] = [
    'name' => $report_title,
    'title'=> $report_title,
    'heading' => $report_title,
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

            {!! Form::open(['route' => ['member.report.warehouse_purchase_report'],'method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>  Select Company </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}
                            </div>
                        @endif


                            @if(config('settings.warehouse'))
                                <div class="col-md-3">
                                    <label>  Warehouse Name </label>
                                    {!! Form::select('warehouse_id', $warehouses, null,['id'=>'warehouse_id','class'=>'form-control select2','placeholder'=>'Select All']); !!}
                                </div>
                            @endif


                            <div class="col-md-3">
                                <label>  Select Manager </label>
                                {!! Form::select('manager_id', $managers, null,['id'=>'manager_id','class'=>'form-control select2','placeholder'=>'Select Manager']); !!}
                            </div>
                            <div class="col-md-3">
                                <label>  Product Name </label>
                                {!! Form::select('item_id', $products, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>'Select All']); !!}
                            </div>


                        <div class="col-md-3">
                            <label> From Date </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label> To Date</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
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
                <div class="box-header with-border">
                    <h3 class="box-title">{{ $report_title }}</h3>
                    <a href="{{  $full_url }}type=print" class="btn btn-sm btn-primary pull-right" id="btn-print"> <i class="fa fa-print"></i> Print </a>
                    <a href="{{  $full_url }}type=download" class="btn btn-sm btn-success pull-right"> <i class="fa fa-download"></i> Download </a>
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="report-table table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th>#SL</th>
                                <th width="100px">Date</th>
                                <th>Transaction Code</th>
                                <th class="text-left">Product Name</th>
                                <th width="70px">Warehouse</th>
                                <th class="text-center">Qty</th>
                                <th>Unit</th>
                                <th class="text-right">Price</th>
                                <th class="text-right">Total Price</th>
                            </tr>
                            @php
                                $last_date = $i = 0;
                                $purchase_total_price = $total_qty = 0;
                            @endphp
                            @foreach($purchases as $key => $value)
                                @php
                                    $warehouse = $value->warehouse($value->id, $value->item_id);
                                @endphp

                                @foreach($warehouse as $warehouseValue)
                                <tr>
                                    <td>{{ ++$i }}</td>
                                    <td width="100px">{{ db_date_month_year_format($value->purchases->date) }}</td>
                                    <td class="text-left">{{ $value->purchases->transaction->transaction_code }}</td>
                                    <td class="text-left">{{ $value->item->item_name }}</td>
                                    <td width="70px">{{ $warehouseValue->warehouse->title }}</td>
                                    <td>{{ $value->unit }}</td>
                                    <td class="text-center">{{ $warehouseValue->qty }}</td>
                                    <td class="text-right">{{ create_money_format($value->price) }}</td>
                                    <td class="text-right">{{ create_money_format($warehouseValue->qty*$value->price) }}</td>
                                </tr>
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 text-right">
                        {{ $purchases->links() }}
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
