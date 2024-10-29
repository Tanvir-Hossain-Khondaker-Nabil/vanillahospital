<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/28/2019
 * Time: 12:22 PM
 */


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('member.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Sales',
        'href' => route('member.purchase.index'),
    ],
    [
        'name' => "Sale Return",
    ],
];

$data['data'] = [
    'name' => 'Sale Return Code: '.$sale[0]->return_code,
    'title'=>'Sale Return Code no: '.$sale[0]->return_code,
    'heading' => trans('common.sale_return_code').': '.$sale[0]->return_code,
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')


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
                                    <th colspan="6">{{__('common.sale_return_details')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>{{__('common.sale_code')}}</th>
                                    <td>{{ $sale[0]->sale_code }}</td>
                                    <th>{{__('common.chalan')}}</th>
                                    <td>{{ $sale[0]->chalan }}</td>
                                    <th>{{__('common.date')}}</th>
                                    <td>{{ db_date_month_year_format($sale[0]->return_date) }}</td>
                                </tr>
                                <tr>
                                    @if($sale[0]->customer)
                                    <th>{{__('common.customer')}}</th>
                                    <td>{{ $sale[0]->customer->name }}</td>
                                    @endif
                                    <th>{{__('common.account_name')}}</th>
                                    <td>{{ $sale[0]->cash_or_bank->title }}</td>
                                    <th>{{__('common.payment_method')}}</th>
                                    <td>{{ $sale[0]->payment_method->name }}</td>
                                </tr>

                                </tbody>
                            </table>
                            <table class="table table-responsive table-bordered margin-top-30 float-right" >
                                <tbody>
                                <tr>
                                    <th>{{__('common.item_name')}}</th>
                                    <th>{{__('common.unit')}}</th>
                                    <th>{{__('common.return_qty')}}</th>
                                    <th>{{__('common.return_price')}}</th>
                                    <th class="text-center">{{__('common.total_price')}}</th>
                                </tr>
                                @php $total = 0; @endphp
                                @foreach($sale as $value)
                                    <tr>
                                        <td>{{ $value->item_name }}</td>
                                        <td>{{ $value->unit }}</td>
                                        <td> {{ $value->return_qty }}</td>
                                        <td> {{ create_money_format($value->return_price) }}</td>
                                        <td class="text-right pr-5" >{{ create_money_format($value->return_qty*$value->return_price) }}</td>
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
                                    <td>@php print_r($sale[0]->notation) @endphp</td>
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

