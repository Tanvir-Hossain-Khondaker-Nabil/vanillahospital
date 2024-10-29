<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/18/2019
 * Time: 3:32 PM
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
                    <h3 class="box-title">{{__('common.search')}}</h3>
                </div>

            {!! Form::open(['route' => ['member.report.product_purchase_report', $search_type],'method' => 'GET', 'role'=>'form' ]) !!}
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
                                    {!! Form::select('warehouse_id', $warehouses, null,['id'=>'warehouse_id','class'=>'form-control select2','placeholder'=>trans('common.select_company')]); !!}
                                </div>
                            @endif


                            @if($search_type == "manager")
                                <div class="col-md-3">
                                    <label>  {{__('common.select_manager')}} </label>
                                    {!! Form::select('manager_id', $managers, null,['id'=>'manager_id','class'=>'form-control select2','placeholder'=>trans('common.select_manager')]); !!}
                                </div>
                            @else
                                <div class="col-md-3">
                                    <label>  {{__('common.product_name')}} </label>
                                    {!! Form::select('item_id', $products, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                                </div>
                            @endif

                        <div class="col-md-3">
                            <label> {{__('common.from_date')}} </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label> {{__("common.to_date")}}</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>

                        <div class="col-md-3 margin-top-23">
                            <label></label>
                            <input class="btn btn-info" value="{{__('common.search')}}" type="submit"/>
                            <a href="{{ route(Route::current()->getName(), $search_type) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> {{__('common.reload')}}</a>

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
                    <a href="{{  $full_url }}type=print" class="btn btn-sm btn-primary pull-right" id="btn-print"> <i class="fa fa-print"></i> {{__('common.print')}} </a>
                    {{-- <a href="{{  $full_url }}type=download" class="btn btn-sm btn-success pull-right"> <i class="fa fa-download"></i> {{__('common.download')}} </a> --}}
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="report-table table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th>#{{__('common.serial')}}</th>
                                <th>{{__('common.delivery_order')}}</th>
                                <th width="100px">{{__('common.date')}}</th>
                                <th width="70px">{{__('common.folio_no')}}</th>
                                <th class="text-left">{{__('common.product_name')}}</th>
                                <th>{{__('common.unit')}}</th>
                                <th class="text-center">{{__("common.quantity")}}</th>
                                <th class="text-right">{{__('common.price')}}</th>
                                <th class="text-right">{{__('common.total_price')}}</th>
                            </tr>
                            @php
                                $last_date = 0;
                                $purchase_total_price = $total_qty = 0;
                            @endphp
                            @foreach($purchases as $key => $value)
                                @if($last_date!=0 && $last_date!=db_date_month_year_format($value->purchases->date))
                                    <tr class=" margin-bottom-20">
                                        <th colspan="5" class="text-right">Total</th>
                                        <th colspan="3" class="text-right">{{ create_money_format($purchase_total_price) }}</th>
                                    </tr>
                                    @php
                                        $purchase_total_price = 0;
                                    @endphp
                                @endif
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->id }}</td>
                                    <td width="100px">{{ db_date_month_year_format($value->purchases->date) }}</td>
                                    <td width="70px">{{ $value->item->productCode }}</td>
                                    <td class="text-left">{{ $value->item->item_name }}</td>
                                    <td>{{ $value->unit }}</td>
                                    <td class="text-center">{{ $value->qty }}</td>
                                    <td class="text-right">{{ create_money_format($value->price) }}</td>
                                    <td class="text-right">{{ create_money_format($value->total_price) }}</td>
                                </tr>
                                @php
                                    $last_date = db_date_month_year_format($value->purchases->date);
                                    $purchase_total_price += $value->total_price;
                                    $total_qty += $value->qty;
                                @endphp

                                @if( $loop->last)
                                    <tr class=" margin-bottom-20">
                                        <th colspan="{{ $item ? 5 : 6 }}" class="text-right">{{__('common.total')}}</th>
                                        @if($item)<th colspan="1" class="text-right">{{ $total_qty." ".$value->unit }}</th>@endif
                                        <th colspan="3" class="text-right">{{ create_money_format($purchase_total_price) }}</th>
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
