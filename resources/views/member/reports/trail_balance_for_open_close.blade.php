<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/9/2019
 * Time: 3:07 PM
 */

 $route = \Auth::user()->can(Route::current()->getName()) ? route(Route::current()->getName()): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => "Trail Balance",
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => "Trail Balance",
    'title'=> "Trail Balance",
    'heading' => "Trail Balance",
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

            {!! Form::open(['route' => 'member.report.trail_balance','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>  Select Company </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}
                            </div>
                        @endif
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
                        <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" class="btn btn-sm btn-primary pull-right" id="btn-print"> <i class="fa fa-print"></i> Print </a>
                        <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" class="btn btn-sm btn-success pull-right"> <i class="fa fa-download"></i> Download </a>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12" id="custom-print">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th class="border-right-1 ">#SL</th>
                                <th class="border-right-1 " >Head of Accounts </th>
                                <th class="border-right-1 ">LF. No. </th>
                                <th class="border-right-1 "> Opening Balance </th>
                                <th class="border-right-1 text-right">DR. TK.</th>
                                <th class="border-right-1 text-right">CR. TK.</th>
                                <th class="border-right-1 "> Closing Balance </th>
                                {{--                                <th class="border-right-1  text-right">Balance</th>--}}
                            </tr>
                            @php
                                $total_dr = 0;
                                $total_cr = 0;
                                $cr_amount = '-';
                                $dr_amount = '-';
                                        $i = 1;
                                $last_cr = $last_dr = 0;
                            @endphp
                            @php
                               // This Cash in Hand will be Credit.
                                $last_cr = $total_cr = $cash_balance >= 0 ? $cash_balance : 0;
                                $last_dr = $total_dr = $cash_balance < 0 ? $cash_balance*(-1) : 0;
                            @endphp
                            <tr>
                                <td class="border-right-1">{{ $i }}</td>
                                <th class="border-right-1">Cash In Hand({{ $cash_date }})</th>
                                <th class="border-right-1"> 4 </th>
                                <th class="border-right-1"> Not Added </th>
                                <th class="border-right-1 text-right">{{ $total_dr < 0 ? create_money_format($total_dr) : '' }}</th>
                                <th class="border-right-1 text-right">{{ $total_cr >= 0 ? create_money_format($total_cr) : '' }}</th>
                                <th class="border-right-1">  Not Added </th>
{{--                                <td class="border-right-1 text-right ">{{  $last_dr }}</td>--}}
{{--                                <td class="border-right-1 text-right ">{{ $last_cr }}</td>--}}

                                {{--                                <th class="border-right-1 text-right">{{ create_money_format($total_cr-$total_dr) }}</th>--}}
                            </tr>

                            @foreach($account_types as $key1=>$account_type)
                                @php
                                    $cr_amount = '';
                                    $dr_amount = '';
                                    $balance = 0.00;
                                @endphp
                                @foreach($modal as $key2=>$value2)

                                    @if($value2->account_type_id == $account_type->id)

                                        @php
                                            $dr_amount = $value2->transaction_type=='dr' ? create_money_format($value2->total_amount): $dr_amount;
                                            $cr_amount = $value2->transaction_type=='cr' ? create_money_format($value2->total_amount): $cr_amount;

                                            $balance = $value2->transaction_type=='dr' ? $balance+$value2->total_amount : $balance-$value2->total_amount;

                                           /**
                                            $total_dr += $value2->transaction_type=='dr' ? $value2->total_amount : 0;
                                            $total_cr += $value2->transaction_type=='cr' ? $value2->total_amount : 0;
                                            **/



                                        @endphp
                                    @endif
                                @endforeach



                                @php
                                    $class = $dr_amount != "-" || $cr_amount != "-" ? "text-bold":"";
                                @endphp
                                @if( $dr_amount > 0 || $cr_amount > 0)

                                    @php
                                        $total_dr += $balance>0 ? $balance : 0;
                                        $total_cr += $balance<0 ? $balance*(-1) : 0;
                                        $last_dr += $balance>0 ? $balance : 0;
                                        $last_cr += $balance<0 ? $balance*(-1) : 0;
                                    @endphp

                                    <tr>
                                        <td class="border-right-1 {{$class}}">{{ $i = $i+1 }}</td>
                                        <td class="border-right-1 {{$class}}">{{ $account_type->display_name }}</td>
                                        <th class="border-right-1 {{$class}}">{{ $account_type->id }}</th>
                                        <td class="border-right-1 ">{{ $general_ledger_accounts[$account_type->id ]['opening_balance']." ".$general_ledger_accounts[$account_type->id ]['opening_type'] }}</td>
                                        <td class="border-right-1 text-right {{$class}}">{{ $balance>0 ? create_money_format($balance) : "" }}</td>
                                        <td class="border-right-1 text-right {{$class}}">{{ $balance<0 ? create_money_format($balance*(-1)) : "" }}</td>
                                        <td class="border-right-1 text-right ">{{ $general_ledger_accounts[$account_type->id ]['closing_balance']." ".$general_ledger_accounts[$account_type->id ]['closing_type'] }}</td>
{{--                                        <td class="border-right-1 text-right {{$class}}">{{  $last_dr }}</td>--}}
{{--                                        <td class="border-right-1 text-right {{$class}}">{{ $last_cr }}</td>--}}
                                        {{--                                    <td class="border-right-1 text-right {{$class}}">{{create_money_format($balance)}}</td>--}}
                                    </tr>
                                @endif

                            @endforeach


                            @foreach($cash_accounts as $key1=>$account_type)
                                @php
                                    $cr_amount = '';
                                    $dr_amount = '';
                                    $balance = 0.00;
                                @endphp
                                @foreach($modal_cash as $key2=>$value3)

                                    @if($value3->account_type_id == $account_type->id)

                                        @php
                                            $dr_amount = $value3->transaction_type=='dr' ? create_money_format($value3->total_amount): $dr_amount;
                                            $cr_amount = $value3->transaction_type=='cr' ? create_money_format($value3->total_amount): $cr_amount;

                                            $balance = $value3->transaction_type=='dr' ? $balance+$value3->total_amount : $balance-$value3->total_amount;


                                        @endphp
                                    @endif
                                @endforeach


                                @foreach($cash_banks as $val)
{{--                                    @if($val['account_type_id'] == $account_type->id)--}}

                                        @php
                                            $class = $dr_amount != "-" || $cr_amount != "-" ? "text-bold":"";
                                        @endphp
                                        @if( $dr_amount > 0 || $cr_amount > 0)

                                            @php
                                                 /*
                                                     $total_dr += $balance>0 ? $val['balance']+$balance : $balance+$val['balance'];
                                                     $total_cr += $balance>0 ? 0 : 0;
                                                     $last_dr += $balance>0 ? $val['balance']+$balance : $balance+$val['balance'];
                                                     $last_cr += $balance<0 ? 0 : 0;
                                                 */

                                              $total_dr += $balance>0 ? $balance : 0;
                                                $total_cr += $balance<0 ? $balance*(-1) : 0;
                                                $last_dr += $balance>0 ? $balance : 0;
                                                $last_cr += $balance<0 ? $balance*(-1) : 0;
                                            @endphp
                                            <tr>
                                                <td class="border-right-1 {{$class}}">{{ $i = $i+1 }}</td>
                                                <td class="border-right-1 {{$class}}">{{ $account_type->display_name }}</td>
                                                <th class="border-right-1 {{$class}}">{{ $account_type->id }}</th>
                                                <td class="border-right-1 ">{{ $cash_banks[$account_type->id ]['opening_balance']." ".$cash_banks[$account_type->id ]['opening_type'] }}</td>
                                                <td class="border-right-1 text-right {{$class}}">{{ $balance>0 ? create_money_format($balance) : "" }}</td>
                                                <td class="border-right-1 text-right {{$class}}">{{ $balance<0 ? create_money_format($balance*(-1)) : "" }}</td>

                                                {{--                                                <td class="border-right-1 text-right {{$class}}">{{ $balance>0 ? create_money_format($val['balance']+$balance) :create_money_format($balance+$val['balance'])}}</td>--}}
{{--                                                <td class="border-right-1 text-right {{$class}}">{{ $balance<0 ? '' : "" }}</td>--}}
                                                <td class="border-right-1 text-right ">{{ $cash_banks[$account_type->id ]['closing_balance']." ".$cash_banks[$account_type->id ]['closing_type'] }}</td>

                                                {{--                                            <td class="border-right-1 text-right {{$class}}">{{ $balance<0 ? create_money_format($balance*(-1)) : "" }}</td>--}}
{{--                                            <td class="border-right-1 text-right {{$class}}">{{create_money_format($balance)}}</td>--}}
                                            </tr>
                                        @endif
{{--                                    @endif--}}
                                @endforeach

                            @endforeach

                            <tr>
                                <th colspan="4" CLASS="text-right">Total</th>
                                <th class="border-right-1 text-right">{{ create_money_format($total_dr)}}</th>
                                <th class="border-right-1 text-right">{{ create_money_format($total_cr) }}</th>
                                {{--                                <th class="border-right-1 text-right">{{ create_money_format($total_cr-$total_dr) }}</th>--}}

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
