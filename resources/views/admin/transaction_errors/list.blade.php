<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/21/2019
 * Time: 3:09 PM
 */

 $route =  \Auth::user()->can(['admin.transaction.index']) ? route('admin.transaction.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Transaction Errors',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Transaction Errors ',
    'title'=>'List Of Transaction Errors ',
    'heading' => trans('common.list_of_transaction_errors'),
];
$transaction_ids = $transaction_codes = '';
?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->
                        <table class="table table-striped table-responsive">
                            <tr>
                                <th>#SL</th>
                                <th>{{__('common.transaction_id')}}</th>
                                <th>{{__('common.transaction_code')}}</th>
                                <th>{{__('common.company_name')}}</th>
                            </tr>
                        @foreach($transactions as $key=>$value)
                            @php
                               // $transaction_ids .= $value->transaction_id.", ";
                               // $transaction_codes .= $value->transaction_code.", ";
                            @endphp
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->transaction_id }}</td>
                                    <td>{{ $value->transaction_code }}</td>
                                    <td>{{ ucfirst($value->company_name) }}</td>
                                </tr>
                        @endforeach

                        </table>
                        <div class="col-md-12 text-right">
{{--                            {{ $transactions->links() }}--}}
                        </div>
{{--                        <div class="row">--}}
{{--                            <div class="col-md-2">--}}
{{--                                <b> Transaction Id</b>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-10">--}}
{{--                                {{ $transaction_ids }}--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <hr/>--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-2">--}}
{{--                                <b> Transaction Code</b>--}}
{{--                            </div>--}}
{{--                            <div class="col-md-10">--}}
{{--                                {{ $transaction_codes }}--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
