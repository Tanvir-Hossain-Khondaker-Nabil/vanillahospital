<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/13/2019
 * Time: 4:16 PM
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
    'heading' => 'Purchase Return: '.$purchase[0]->memo_no,
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')

    {{--<div class="row text-right">--}}
        {{--<div class="col-md-12">--}}
            {{--<div class="box">--}}
                {{--<div class="box-body">--}}
                    {{--<a href="javascript:void(0)" class="btn btn-xs btn-primary" id="print"><i class="fa fa-print"></i> Print</a>--}}

                    {{--<a href="{{ route('member.purchase_return.edit', $purchase[0]->id) }}" class="btn btn-xs btn-info">--}}
                        {{--<i class="fa fa-reply"></i> Purchase Return--}}
                    {{--</a>--}}

                    {{--<a href="{{ route('member.purchase.edit',  $purchase[0]->id) }}" class="btn btn-xs btn-success">--}}
                        {{--<i class="fa fa-pencil"></i> Edit--}}
                    {{--</a>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
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
                                        <th colspan="6">Purchase Return Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>Memo No</th>
                                        <td>{{ $purchase[0]->memo_no }}</td>
                                        <th>Chalan</th>
                                        <td>{{ $purchase[0]->chalan }}</td>
                                        <th>Date</th>
                                        <td>{{ db_date_month_year_format($purchase[0]->return_date) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Supplier</th>
                                        <td>{{ $purchase[0]->supplier->name }}</td>
                                        <th>Account Name</th>
                                        <td>{{ $purchase[0]->cash_or_bank->title }}</td>
                                        <th>Payment Method</th>
                                        <td>{{ $purchase[0]->payment_method->name }}</td>
                                    </tr>

                                </tbody>
                            </table>
                            <table class="table table-responsive table-bordered margin-top-30 float-right" >
                                <tbody>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Unit</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th class="text-center">Total Price</th>
                                    </tr>
                                    @php $total = 0; @endphp
                                    @foreach($purchase[0]->purchase_returns as $value)
                                    <tr>
                                        <td>{{ $value->item->item_name }}</td>
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
                                    <th> Notes: </th>
                                </tr>
                                <tr>
                                    <td>@php print_r($purchase[0]->notation) @endphp</td>
                                </tr>
                            </table>
                            <table class=" margin-top-30 pull-right" width="400px">

                                <tr>
                                    <th class="text-right" colspan="5"> Total Return Amount</th>
                                    <th class="text-right" > {{ create_money_format($total) }} </th>
                                </tr>

                            </table>
                        </div>
                    </div>
                    <div class="text-left margin-top-30">
                        <a href="javascript:void(0)" class="btn btn-primary" id="print"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
