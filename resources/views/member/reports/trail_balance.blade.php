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
        'name' => "Reports",
        'href' => $route,
    ],
    [
        'name' => 'Trail Balance',
    ],
];

$data['data'] = [
    'name' => "Trail Balance",
    'title'=> "Trail Balance",
    'heading' =>trans('common.trail_balance'),
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

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>

            {!! Form::open(['route' => 'member.report.trail_balance','method' => 'GET', 'role'=>'form' ]) !!}
                <input name="search" value="1" type="hidden">
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>{{__('common.select_company')}} </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}
                            </div>
                        @endif
{{--                        <div class="col-md-3">--}}
{{--                            <label>  Head of Accounts </label>--}}
{{--                            {!! Form::select('account_type_id', $accounts, null,['id'=>'account_type_id','class'=>'form-control select2','placeholder'=>'Select All']); !!}--}}
{{--                        </div>--}}
                        <div class="col-md-3">
                            <label>{{__('common.from_date')}}  ({{__('common.transaction')}})</label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label> {{__('common.to_date')}}  ({{__('common.transaction')}})</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>
                            <div class="col-md-3 margin-top-30">
                                <input type="checkbox" class="" name="opening_balance" value="1" {{ $opening_balance ? "checked" : "" }}/>
                                <label>{{__('common.with_opening_balance')}} </label>
                            </div>
                    </div>
                    <!-- /.row -->
                </div>
                <div class="box-body">

                    <div class="col-md-3">
                        <input class="btn  btn-sm btn-info" value="{{__('common.search')}}" type="submit"/>
                        <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> {{__('common.reload')}}</a>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <div class="col-12 text-center">
                        <h3 class="box-title"> {!! $report_title !!} </h3>
                    </div>

                    <div class="col-12 pull-right " style="padding: 0 20px;">
                        <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" class="btn btn-sm btn-primary pull-right" id="btn-print"> <i class="fa fa-print"></i>{{__('common.print')}}  </a>
                        <a href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" class="btn btn-sm btn-success pull-right"> <i class="fa fa-download"></i>{{__('common.download')}}  </a>
                    </div>
                </div>

                <div class="box-body">


                    <div class="col-lg-9" id="custom-print">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th class="border-right-1 ">#{{__('common.serial')}}</th>
                                <th class="border-right-1 ">{{__('common.head_of_accounts')}}</th>
                                <th class="border-right-1 ">LF. No. </th>
                                <th class="border-right-1 text-right">{{__('common.debit')}} {{__('common.amount')}}</th>
                                <th class="border-right-1 text-right">{{__('common.credit')}} {{__('common.amount')}}</th>
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
                                if(isset($cash_balance)){
                                   // This Cash in Hand will be Credit.
                                    $last_cr = $total_cr = $cash_balance >= 0 ? $cash_balance : 0;
                                    $last_dr = $total_dr = $cash_balance < 0 ? $cash_balance : 0;
                            @endphp
                            <tr>
                                <td class="border-right-1">{{ $i }}</td>
                                <th class="border-right-1">{{__('common.cash_in_hand')}}({{ $cash_date }})</th>
                                <th class="border-right-1"> 4 </th>
                                <th class="border-right-1 text-right">{{ $total_dr > 0 ? create_money_format($total_dr) : '' }}</th>
                                <th class="border-right-1 text-right">{{ $total_cr > 0 ? create_money_format($total_cr) : '' }}</th>
                                {{--                                <td class="border-right-1 text-right ">{{  $last_dr }}</td>--}}
                                {{--                                <td class="border-right-1 text-right ">{{ $last_cr }}</td>--}}

                                {{--                                <th class="border-right-1 text-right">{{ create_money_format($total_cr-$total_dr) }}</th>--}}
                            </tr>
                            @php
                                }
                            @endphp
                            @foreach($account_types as $key1=>$account_type)
                                @php
                                    $cr_amount = '';
                                    $dr_amount = '';
                                    $balance = 0.00;
                                @endphp
                                @foreach($modal as $key2=>$value2)

                                    @if($value2->id == $account_type->id && $value2->total_amount >0 )

                                        @php
                                            $dr_amount = $value2->transaction_type=='dr' ? create_money_format($value2->total_amount): $dr_amount;
                                            $cr_amount = $value2->transaction_type=='cr' ? create_money_format($value2->total_amount): $cr_amount;

                                            $balance = $value2->transaction_type=='dr' ? $balance+$value2->total_amount : $balance-$value2->total_amount;

                                        @endphp
                                    @endif
                                @endforeach




                                @php
                                    $class = $dr_amount != "-" || $cr_amount != "-" ? "text-bold":"";
                                @endphp
                                @if(( $dr_amount > 0 || $cr_amount > 0) && $balance!=0)

                                    @php
                                        $total_dr += $balance>0 ? $balance : 0;
                                        $total_cr += $balance<0 ? $balance*(-1) : 0;
                                    @endphp
                                    <tr>
                                        <td class="border-right-1 {{$class}}">{{ $i = $i+1 }}</td>
                                        <td class="border-right-1 {{$class}}">{{ $account_type->display_name }}</td>
                                        <th class="border-right-1 {{$class}}">{{ $account_type->id }}</th>
                                        <td class="border-right-1 text-right {{$class}}">{{ $balance>0 ? create_money_format($balance) : "" }}</td>
                                        <td class="border-right-1 text-right {{$class}}">{{ $balance<0 ? create_money_format($balance*(-1)) : "" }}</td>

                                    </tr>
                                @endif

                            @endforeach


                            @foreach($cash_accounts as $key1=>$account_type)
                                @php
                                    $cr_amount = '';
                                    $dr_amount = '';
                                    $balance = 0.00;
                                @endphp
                                @foreach($modal_cash as $key2=>$value2)

                                    @if($value2->id == $account_type->id)

                                        @php
                                            $dr_amount = $value2->transaction_type=='dr' ? create_money_format($value2->total_amount): $dr_amount;
                                            $cr_amount = $value2->transaction_type=='cr' ? create_money_format($value2->total_amount): $cr_amount;

                                            $balance = $value2->transaction_type=='dr' ? $balance+$value2->total_amount : $balance-$value2->total_amount;


                                        @endphp
                                    @endif
                                @endforeach


                                @foreach($cash_banks as $val)
                                    @if($val['account_type_id'] == $account_type->id)

                                        @php
                                            $class = $dr_amount != "-" || $cr_amount != "-" ? "text-bold":"";
                                        @endphp
                                        @if( $dr_amount > 0 || $cr_amount > 0)

                                            @php
                                                $total_dr += $balance>0 ? $val['balance']+$balance : $balance+$val['balance'];
                                                $total_cr += $balance>0 ? 0 : 0;
                                            @endphp
                                            <tr>
                                                <td class="border-right-1 {{$class}}">{{ $i = $i+1 }}</td>
                                                <td class="border-right-1 {{$class}}">{{ $account_type->display_name }} cc</td>
                                                <th class="border-right-1 {{$class}}">{{ $account_type->id }} vbb</th>
                                                <td class="border-right-1 text-right {{$class}}">{{ $balance>0 ? create_money_format($val['balance']+$balance) :create_money_format($balance+$val['balance'])}}</td>
                                                <td class="border-right-1 text-right {{$class}}">{{ $balance<0 ? '' : "" }}</td>
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach

                            @endforeach

                            <tr>
                                <th colspan="3">{{__('common.total')}}</th>
                                <th class="border-right-1 text-right">{{ create_money_format($total_dr)}}</th>
                                <th class="border-right-1 text-right">{{ create_money_format($total_cr) }}</th>
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
