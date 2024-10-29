<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
* Date: 5/20/2019
* Time: 3:58 PM
*/



$route =  \Auth::user()->can(['member.purchase.index']) ? route('member.purchase.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Purchases',
        'href' => $route,
    ],
    [
        'name' => $purchase[0]->memo_no,
    ],
];

$data['data'] = [
    'name' => 'Purchase Return: '.$purchase[0]->memo_no,
    'title'=>'Purchase Return: Memo no: '.$purchase[0]->memo_no,
    'heading' => trans('purchase.purchase_return').': '.$purchase[0]->memo_no,
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row text-right">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <a href="javascript:void(0)" class="btn btn-xs btn-primary" id="print"><i class="fa fa-print"></i> {{__('common.print')}}</a>

                    <a href="{{ route('member.purchase_return.edit', $purchase[0]->purchase_id) }}" class="btn btn-xs btn-info">
                        <i class="fa fa-reply"></i> {{__('purchase.purchase_return')}}
                    </a>

                    {{--<a href="{{ route('member.purchase.edit',  $purchase[0]->purchase_id) }}" class="btn btn-xs btn-success">--}}
                        {{--<i class="fa fa-pencil"></i> Edit--}}
                    {{--</a>--}}
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">

                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive" id="custom-print">
                            <table class="table table-responsive table-striped table-bordered ">
                                <thead class="text-center">
                                <tr>
                                    <th colspan="6">{{__('purchase.purchase_return_details')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>{{__('common.memo_no')}}</th>
                                    <td>{{ $purchase[0]->memo_no }}</td>
                                    <th>{{__('common.chalan')}}</th>
                                    <td>{{ $purchase[0]->chalan }}</td>
                                    <th>{{__('common.date')}}</th>
                                    <td>{{ db_date_month_year_format($purchase[0]->return_date) }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('common.supplier')}}</th>
                                    <td>{{ $purchase[0]->supplier->name }}</td>
                                    <th>{{__('common.account_name')}}</th>
                                    <td>{{ $purchase[0]->cash_or_bank->title }}</td>
                                    <th>{{__('common.payment_method')}}</th>
                                    <td>{{ $purchase[0]->payment_method->name }}</td>
                                </tr>

                                </tbody>
                            </table>
                            <table class="table table-responsive table-bordered margin-top-30 float-right" >
                                <tbody>
                                <tr>
                                    <th>{{__('common.item_name')}}</th>
                                    <th>{{__('common.unit')}}</th>
                                    <th>{{__('common.qty')}}</th>
                                    <th>{{__('common.price')}}</th>
                                    <th class="text-center">{{__("common.total_price")}}</th>
                                </tr>
                                @php $total = 0; @endphp
                                @foreach($purchase as $value)
                                    <tr>
                                        <td>{{ $value->item_name }}</td>
                                        <td>{{ $value->unit }}</td>
                                        <td> {{ $value->return_qty }}</td>
                                        <td> {{ create_money_format($value->return_price) }}</td>
                                        <td class="text-right" >{{ create_money_format($value->return_qty*$value->return_price) }}</td>
                                    </tr>
                                    @php
                                        $total += ($value->return_qty*$value->return_price);
                                    @endphp
                                @endforeach
                                </tbody>

                            </table>
                            <table class="margin-top-30" style="margin-bottom:50px; width: 700px; float: left;">
                                <tr>
                                    <th> {{__('common.notes')}}: </th>
                                </tr>
                                <tr>
                                    <td>@php print_r($purchase[0]->notation) @endphp</td>
                                </tr>
                            </table>
                            <table class=" margin-top-30 pull-right" width="400px">

                                <tr>
                                    <th class="text-right" colspan="5"> {{__('common.total_return_amount')}}</th>
                                    <th class="text-right" > {{ create_money_format($total) }} </th>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <div class="text-left margin-top-30">
                        <a href="javascript:void(0)" class="btn btn-primary" id="print"><i class="fa fa-print"></i> {{__('common.print')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
