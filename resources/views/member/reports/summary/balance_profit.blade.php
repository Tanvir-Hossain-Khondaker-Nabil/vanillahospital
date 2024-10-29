<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/20/2021
 * Time: 5:09 PM
 */

 $route = \Auth::user()->can(['member.report.total-sales-purchases']) ? route('member.report.total-sales-purchases') : '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Stocks',
        'href' => $route,
    ],
    [
        'name' => "Balance Profit",
    ],
];

$data['data'] = [
    'name' => "Balance Profit",
    'title'=> "Balance Profit",
    'heading' => trans('common.balance_profit'),
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

            {!! Form::open(['route' => 'member.report.balance-profit','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

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
                    <h3 class="box-title">{!! $report_title !!} </h3>

                    @include('member.reports.print_title_btn')
                </div>



                <div class="box-body">

                    <div class="col-lg-6 ">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th> {{__('common.purchase')}} </th>
                                <td class="text-right">{{  create_money_format($total_purchase) }}</td>
                            </tr>
                            <tr>
                                <th> {{__('common.due_purchase')}} </th>
                                <td class="text-right">(+) {{  create_money_format($purchase_due) }}</td>
                            </tr>
                            <tr>
                                <th>  {{__("common.purchase_discount")}}</th>
                                <td class="text-right"> {{  create_money_format($purchase_discount) }}</td>
                            </tr>
                            <tr>
                                <th> {{__('common.purchase_return')}} </th>
                                <td class="text-right">(-) {{ create_money_format($total_purchase_return) }}</td>
                            </tr>
                            @php
                                $purchase_amount = $total_purchase+$purchase_due-$total_purchase_return;
                            @endphp
                            <tr>
                                <th >  {{__('common.total_purchase_amount')}}</th>
                                <td class="text-right double-line">{{  create_money_format($purchase_amount) }}</td>
                            </tr>
                            <tr>
                                <th class=" " > {{__('common.total_utility_cost')}}  </th>
                                <td class="text-right single-line">{{  create_money_format(config('settings.utility_cost')) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6 ">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th> {{__('common.sale')}} </th>
                                <td class="text-right">{{ create_money_format($total_sale) }}</td>
                            </tr>
                            <tr>
                                <th> {{__('common.sale_due')}} </th>
                                <td class="text-right">(+) {{ create_money_format($sale_due) }}</td>
                            </tr>
                            <tr>
                                <th> {{__("common.sale_discount")}} </th>
                                <td class="text-right">{{ create_money_format($sale_discount) }}</td>
                            </tr>
                            <tr>
                                <th> {{__('common.sale_return')}} </th>
                                <td class="text-right">(-) {{  create_money_format($total_sale_return) }}</td>
                            </tr>
                            @php
                                $sale_amount = $total_sale+$sale_due-$total_sale_return;
                                 $total =    $sale_amount-$purchase_amount-config('settings.utility_cost');
                            @endphp
                            <tr>
                                <th> {{__('common.total_sale')}}  </th>
                                <td class="text-right">{{  create_money_format($sale_amount) }}</td>
                            </tr>

                            <tr>
                                <th> <?=$total<0 ? trans('common.loss') : trans('common.profit')?>  </th>
                                <th class="text-right dual-line <?=$total<0 ? 'font-italic text-red' : ''?>">{{  ($total<0 ? "(":"").create_money_format($total).($total<0 ? ")":"") }}</th>
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

    <script type="text/javascript">

        $(document).ready( function(){
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
