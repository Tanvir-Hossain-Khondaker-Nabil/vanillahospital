<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 11/25/2019
 * Time: 3:31 PM
 */

 $route =  \Auth::user()->can(['member.reconciliation.index']) ? route('member.reconciliation.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

 $data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Reconciliation List',
        'href' => $route,
    ],
    [
        'name' => $general_ledger['transaction_code'],
    ],
];

$data['data'] = [
    'name' => 'Transaction Code: '.$general_ledger['transaction_code'],
    'title'=>'Transaction Code: '.$general_ledger['transaction_code'],
    'heading' => 'Transaction Code: '.$general_ledger['transaction_code'],
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
                                    <th colspan="5">Transaction Details</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>Transaction Code</th>
                                    <th>Transaction Date</th>
                                    <th>Transaction Method</th>
                                    <th colspan="1"> Entry By</th>
                                </tr>
                                <tr>
                                    <td>{{ $general_ledger['transaction_code'] }}</td>
                                    <td>{{ $general_ledger['date'] }}</td>
                                    <td>{{ $general_ledger['method'] }}</td>
                                    <td class="text-capitalize">{{ $general_ledger['entry_by'] }}</td>
                                </tr>

                                </tbody>
                            </table>
                            <table class="table table-responsive table-bordered margin-top-30" >
                                <tbody>
                                <tr>
                                    <th>Jounral Date</th>
                                    <th>Account Code</th>
                                    <th>Account Name</th>
                                    <th class="text-center">Dr</th>
                                    <th class="text-center">Cr</th>
                                    <th>Description</th>
                                </tr>

                                @php
                                    $total_debit = 0;
                                    $total_credit = 0;

                                @endphp

                                @foreach($general_ledger['transaction'] as $key => $value)

                                    <tr>
                                        <td>{{  db_date_month_year_format($value->date) }}</td>
                                        {{--                                    <td>{{ $general_ledger['method'] != "Transfer" && $loop->last ?  $general_ledger['transaction_form_code'] : format_number_digit($value->account_code)   }}</td>--}}
                                        {{--                                    <td>{{  $general_ledger['method'] != "Transfer" && $loop->last ? $general_ledger['transaction_form'] : (($value->account_type_name == "Accounts Receivable" || $value->account_type_name == "Accounts Payable") ? $value->sharer_name : $value->account_type_name) }}</td>--}}
                                        <td>{{ format_number_digit($value->account_code)  }}</td>
                                        <td>{{   $value->account_type_name }}</td>
                                        <td  class="text-right">{{ $value->transaction_type == 'dr' ? create_money_format( $value->amount ) : "" }}</td>
                                        <td class="text-right">{{ $value->transaction_type == 'cr' ?  create_money_format( $value->amount) : ""  }}</td>
                                        <td>{{ $value->description }}</td>
                                    </tr>
                                    @php
                                        $total_debit += $value->transaction_type == 'dr' ? $value->amount : 0;
                                        $total_credit += $value->transaction_type == 'cr' ? $value->amount : 0;
                                    @endphp

                                @endforeach
                                <tr>
                                    <th colspan="3" > Total </th>
                                    <th class="text-right">{{ create_money_format($total_debit) }} <hr class="double-line" /><hr class="double-line" /> </th>
                                    <th class="text-right">{{ create_money_format($total_credit) }} <hr class="double-line" /><hr class="double-line" /> </th>
                                    <th></th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-right">
                        <a href="javascript:void(0)" id="print"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
