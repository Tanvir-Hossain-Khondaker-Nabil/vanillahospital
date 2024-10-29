<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/23/2019
 * Time: 3:57 PM
 */

 $route =  \Auth::user()->can(['member.general_ledger.index']) ? route('member.general_ledger.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'General Ledger',
        'href' => $route,
    ],
    [
        'name' => $journal['transaction_code'],
    ],
];

$data['data'] = [
    'name' => 'GL-Transaction Code: '.$journal['transaction_code'],
    'title'=>'General Ledger | Transaction Code: '.$journal['transaction_code'],
    'heading' =>trans('common.general_ledger')."-".trans('common.transaction_code').": ".$journal['transaction_code'],
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
                                    <th colspan="5">{{__('common.general_ledger_transaction_details')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <th>{{__('common.transaction_code')}} </th>
                                    <th>{{__('common.transaction_date')}} </th>
                                    <th>{{__('common.document_date')}}</th>
                                    <th>{{__('common.event_date')}}</th>

                                </tr>

                                <tr>
                                    <td>{{ $journal['transaction_code'] }}</td>
                                    <td>{{ $journal['date'] }}</td>
                                    <td>{{ $journal['document_date'] }}</td>
                                    <td>{{ $journal['event_date'] }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('common.transaction_method')}} </th>
                                    <th>{{__('common.entry_by')}} </th>

                                    <th colspan="2">{{__('common.source_resource')}} </th>
                                </tr>
                                <tr>
                                    <td>{{ $journal['method'] }}</td>
                                    <td class="text-capitalize">{{ $journal['entry_by'] }}</td>
                                    <td class="text-capitalize" colspan="2">{{ $journal['source_reference'] }}</td>
                                </tr>

                                </tbody>
                            </table>
                            <table class="table table-responsive table-bordered margin-top-30" >
                                <tbody>
                                <tr>
                                    <th>{{__('common.jounral_date')}} </th>
                                    <th>{{__('common.account_code')}}  </th>
                                    <th>{{__('common.account_name')}}</th>
                                    <th class="text-center">{{__('common.debit')}}</th>
                                    <th class="text-center">{{__('common.credit')}}</th>
                                    <th>{{__('common.description')}}  </th>
                                </tr>

                                @php
                                    $total_debit = 0;
                                    $total_credit = 0;

                                @endphp

                                @foreach($journal['transaction'] as $key => $value)

                                    <tr>
                                        <td>{{  db_date_month_year_format($value->date) }}</td>
                                        <td>{{  format_number_digit($value->account_type->id)   }}</td>
                                        <td>{{  $value->account_type->display_name }}</td>
                                        <td  class="text-right">{{ $value->transaction_type == 'dr' ? create_money_format( $value->amount) : "" }}</td>
                                        <td class="text-right">{{   $value->transaction_type == 'cr' ? create_money_format( $value->amount) : "" }}</td>
                                        <td>{{ $value->transaction_details->description }}</td>
                                    </tr>
                                    @php
                                        $total_debit += $value->transaction_type == 'dr' ? $value->amount : 0;
                                        $total_credit += $value->transaction_type == 'cr' ? $value->amount : 0;
                                    @endphp

                                @endforeach
                                <tr>
                                    <th colspan="3" >{{__('common.total')}}   </th>
                                    <th class="text-right">{{ create_money_format($total_debit) }} <hr class="double-line" /><hr class="double-line" /> </th>
                                    <th class="text-right">{{ create_money_format($total_credit) }} <hr class="double-line" /><hr class="double-line" /> </th>
                                    <th></th>
                                </tr>

                                <tr>
                                    <th colspan="1" >{{__('common.notation')}}  : </th>
                                    <td colspan="5"> {{ $journal['notation'] }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="text-right">
                        <a class="btn btn-xs btn-primary pull-right"  style="margin-right: 10px;" href="{{ route('member.journal_entry.print', $journal['transaction_code']) }}" id="btn-print"><i class="fa fa-print"></i> Print</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
