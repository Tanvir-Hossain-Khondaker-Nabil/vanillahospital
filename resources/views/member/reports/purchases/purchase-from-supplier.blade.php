<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/22/2019
 * Time: 11:55 AM
 */

//  $route = \Auth::user()->can(['member.report.lost_profit']) ? route('member.report.lost_profit') : '#';

$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Reports',
        'href' => "#",
    ],
    [
        'name' => 'Purchase Report by Supplier',
    ],
];

$data['data'] = [
    'name' => 'Purchase Report by Supplier',
    'title'=> 'Purchase Report by Supplier',
    'heading' => trans('report.purchase_report_by_supplier'),
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
                    <h3 class="box-title">{{__('common.search')}}</h3>
                </div>

            {!! Form::open(['route' => 'member.report.supplier_purchase','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>  {{__('common.select_company')}} </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('common.select_company')]); !!}
                            </div>
                        @endif


                            @if(config('settings.warehouse'))
                                <div class="col-md-3">
                                    <label>  {{__('common.warehouse_name')}} </label>
                                    {!! Form::select('warehouse_id', $warehouses, null,['id'=>'warehouse_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                                </div>
                            @endif

                        <div class="col-md-3">
                            <label>  {{__('common.supplier_name')}} </label>
                            {!! Form::select('supplier_id', $suppliers, null,['id'=>'supplier_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>

                        <div class="col-md-3">
                            <label>  {{__('common.product_name')}} </label>
                            {!! Form::select('item_id', $products, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>

                        <div class="col-md-3">
                            <label> {{__('common.from_date')}} </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label> {{__('common.to_date')}}</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>

                        <div class="col-md-3 margin-top-23">
                            <label></label>
                            <input class="btn btn-info" value="{{__('common.search')}}" type="submit"/>
                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> {{__('common.reload')}}</a>

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
                    <h3 class="box-title"> {{__('report.purchase_report_by_supplier')}}</h3>
                    <a class="btn btn-sm  btn-primary pull-right mx-3" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" id="btn-print"> <i class="fa fa-print"></i> {{__('common.print')}} </a>
                    {{-- <a class="btn btn-sm btn-success pull-right" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" > <i class="fa fa-download"></i> {{__('common.download')}} </a> --}}
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="report-table table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th>#{{__('common.serial')}}</th>
                                <th width="100px;">{{__('common.date')}}</th>
                                <th>{{__('common.delivery_order')}}</th>
                                <th>{{__('common.supplier_name')}}</th>
                                <th>{{__('common.folio_no')}}</th>
                                <th>{{__('common.product_name')}}</th>
                                <th>{{__('common.unit')}}</th>
                                <th class="text-center">{{__('common.quantity')}}</th>
                                <th class="text-right">{{__('common.price')}}</th>
                                <th class="text-right">{{__('common.total_price')}}</th>
                            </tr>
                            @php
                                $last_date = 0;
                                $purchase_total_price = $total_qty = 0;
                                $product_name = $last_unit = '';
                            @endphp
                            @foreach($purchases as $key => $value)
                                @if( !$loop->first && ($last_date!=0 ||  $last_date != db_date_month_year_format($value->purchases->date)) &&  $product_name!=$value->item->item_name)
                                    <tr class=" margin-bottom-20">
                                        <th colspan="7" class="text-right">{{__('common.total')}}</th>
                                        <th colspan="1" class="text-right">{{ $total_qty." ".$last_unit }}</th>
                                        <th colspan="2" class="text-right">{{ create_money_format($purchase_total_price) }}</th>
                                    </tr>
                                    @php
                                        $purchase_total_price = $total_qty = 0;
                                    @endphp
                                @endif
                                <tr>
                                    <td>{{ $key = $key+1 }}</td>
                                    <td class="text-center">{{ db_date_month_year_format($value->purchases->date) }}</td>
                                    <td></td>
                                    <td>{{ $value->purchases->supplier->name }}</td>
                                    <td></td>
                                    <td>{{ $value->item->item_name }}</td>
                                    <td>{{ $value->unit }}</td>
                                    <td class="text-right padding-right-20">{{ $value->qty }}</td>
                                    <td class="text-right">{{ create_money_format($value->price) }}</td>
                                    <td class="text-right">{{ create_money_format($value->qty*$value->price) }}</td>
                                </tr>
                                @if($value->purchase_status == "return")
                                    @php
                                        $return = $value->purchase_returns($value->purchases->id, $value->item_id)
                                    @endphp
                                    @foreach($return as $value2)

                                        <tr>

                                            <td>{{ $key = $key+1 }}</td>
                                            <td>{{ db_date_month_year_format($value2->purchases->date) }}</td>
                                            <td></td>
                                            <td class="text-left">{{ $value2->purchases->supplier->name }}</td>
                                            <td> {{__('common.purchase_return')}}</td>
                                            <td class="text-left">{{ $value2->item->item_name }}</td>
                                            <td>{{ $value->unit }}</td>
                                            <td class="text-center italic">({{ $value2->return_qty }})</td>
                                            <td class="text-right">({{ create_money_format($value2->return_price) }})</td>
                                            <td class="text-right">({{ create_money_format($value2->return_qty*$value2->return_price) }})</td>
                                        </tr>
                                        @php
                                            $purchase_total_price -= $value2->return_qty*$value2->return_price;
                                            $total_qty -= $value2->return_qty;
                                        @endphp
                                    @endforeach
                                @endif
                                @php
                                    $last_date = db_date_month_year_format($value->purchases->date);
                                    $purchase_total_price += $value->qty*$value->price;
                                    $product_name = $value->item->item_name;
                                    $total_qty += $value->qty;
                                    $last_unit = $value->unit;
                                @endphp

                                @if( $loop->last)
                                    <tr class=" margin-bottom-20">
                                        <th colspan="7" class="text-right">{{__('common.total')}}</th>
                                        <th colspan="1" class="text-right">{{ $total_qty." ".$last_unit }}</th>
                                        <th colspan="2" class="text-right">{{ create_money_format($purchase_total_price) }}</th>
                                    </tr>
                                    @php
                                        $purchase_total_price = 0;
                                    @endphp
                                @endif
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

