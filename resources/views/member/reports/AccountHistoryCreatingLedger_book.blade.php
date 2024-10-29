<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/11/2019
 * Time: 11:34 AM
 */


use App\Models\AccountHeadDayWiseBalance;

$route = \Auth::user()->can(Route::current()->getName()) ? route(Route::current()->getName()): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => "Ledger Book",
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => "Ledger Book",
    'title'=> "Ledger Book",
    'heading' => "Ledger Book",
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

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>

            {!! Form::open(['route' => 'member.report.ledger_book','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label>  Select Company </label>
                            {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company', 'required']); !!}
                        </div>
                        <div class="col-md-3">
                            <label>  Head of Accounts </label>
                            {!! Form::select('account_type_id', $accounts, null,['id'=>'account_type_id','class'=>'form-control select2','placeholder'=>'Select All']); !!}
                        </div>
                        <div class="col-md-3">
                            <label> From Date (Transaction)</label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label> To Date (Transaction)</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <div class="box-body">

                    <div class="col-md-3">
                        <input class="btn  btn-sm btn-info" value="Search" type="submit"/>
                        <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>

            <div class="box">
                <div class="box-body">
                    <div class="text-right" style="padding: 0 20px;">
                        <a href="javascript:void(0)" id="print" data-title="Ledger Book"><i class="fa fa-print"></i> Print</a>
                    </div>

                    <div class="col-lg-12" id="custom-print">

                        @foreach($account_types as $key1=>$account_type)
                            <div class="box" style="overflow: hidden;">
                                <div class="box-header with-border">

                                    <h3 class="box-title" style="margin: 10px 0;">Account Of {{ $account_type->display_name }}</h3>
                                    <table class="table table-bordered" style="margin-bottom: 50px;">

                                        <tbody>

                                        <tr>
                                            <th class="">Date</th>
                                            <th >Account Head</th>
                                            {{--                                        <th class="">Payment Type</th>--}}
                                            {{--                                        <th class="">Cash or Bank Account Name</th>--}}
                                            {{--                                        <th> Supplier/Customer Name</th>--}}
                                            <th>Remarks</th>
                                            <th class="text-right">DR. TK. </th>
                                            <th class="text-right">CR. TK. </th>
                                            <th class="text-right">Balance </th>
                                        </tr>

                                        @php
                                            $total_dr = 0.00;
                                            $total_cr = 0.00;
                                            $balance = 0.00;
                                        @endphp

                                        @foreach($modal as $key2=>$value2)
                                            @if($value2->account_name == $account_type->display_name)



                                                @php
                                                    if($value2->transaction_type=='dr'){
                                                        $total_dr += $value2->td_amount;
                                                    }else{
                                                        $total_cr += $value2->td_amount;
                                                    }

    /*
                                                        $inputs = [];
                                                        $inputs['account_type_id'] = $account_type->id;
                                                        $inputs['account_head_name'] = $account_type->display_name;
                                                        $inputs['previous_balance'] = $balance;
                                                        $inputs['amount'] = $value2->td_amount;
                                                        $inputs['transaction_type'] = $value2->transaction_type;
                                                        $inputs['date'] = \Carbon\Carbon::today();
    */
                                                $balance = $value2->transaction_type=='dr' ? $balance+$value2->td_amount : $balance-$value2->td_amount;

    /*
                                                        $inputs['current_balance'] = $balance;
                                                        $thisData = new \App\Models\AccountHeadDayWiseBalance();
                                                        $thisData->createAccountHeadBalanceHistory($inputs);
    */
                                                @endphp
                                                <tr>
                                                    <td class="">{{ db_date_month_year_format($value2->date) }}</td>
                                                    <td class="">{{ $value2->against_account_name }}</td>
                                                    {{--                                                <td class="">{{ $value2->payment_type_name }}</td>--}}
                                                    {{--                                                <td class="">{{ $value2->title }}</td>--}}
                                                    {{--                                                <td class="">{{ $value2->sharer_name }}</td>--}}
                                                    <td class="">{{ $value2->remarks }}</td>
                                                    <td class="text-right ">{{ $value2->transaction_type=="dr" ? create_money_format($value2->td_amount) : "-"}}</td>
                                                    <td class="text-right ">{{ $value2->transaction_type=="cr" ? create_money_format($value2->td_amount) : "-"}}</td>
                                                    <td class="text-right ">{{ create_money_format($balance) }}</td>
                                                </tr>

                                            @endif
                                        @endforeach

                                        {{--                                    @php--}}

                                        {{--                                        $inputs = [];--}}
                                        {{--                                            $inputs['account_type_id'] = $account_type->id;--}}
                                        {{--                                            $inputs['amount'] = $balance;--}}
                                        {{--                                            $inputs['date'] = \Carbon\Carbon::today();--}}
                                        {{--                                            $thisData = new \App\Models\AccountHeadDayWiseBalance();--}}
                                        {{--                                            $thisData->createAccountHeadDayWiseBalanceHistory($inputs);--}}
                                        {{--                                    @endphp--}}
                                        <tr>
                                            <th colspan="3" class="text-center">Balance C/d</th>
                                            <th class="text-right">{{ create_money_format($total_dr)}}</th>
                                            <th class="text-right">{{ create_money_format($total_cr) }}</th>
                                            <th class="text-right">{{ create_money_format($balance) }}</th>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach

                    </div>


                    <div class="text-right" style="padding: 0 20px;">
                        <a href="javascript:void(0)" id="print"><i class="fa fa-print"></i> Print</a>
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


