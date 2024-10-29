<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/11/2019
 * Time: 11:34 AM
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
    'heading' =>trans('common.ledger_book'),
];

?>

@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')


    <div class="row">
        <div class="col-xs-12">
            @include('common._alert')


            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.search')}} </h3>

                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>

            {!! Form::open(['route' => 'member.report.ledger_book','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>{{__('common.select_company')}}  </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=> trans('common.select_company')]); !!}
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label>{{__('common.head_of_accounts')}}  </label>
                            {!! Form::select('account_type_id', $accounts, null,['id'=>'account_type_id','class'=>'form-control select2','placeholder'=>trans('common.select_all'), 'required']); !!}
                        </div>
                        <div class="col-md-3">
                            <label>{{__('common.from_date')}} ({{__('common.transaction')}} )</label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label>{{__('common.to_date')}}   ({{__('common.transaction')}} )</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <div class="box-body">

                    <div class="col-md-3">
                        <input class="btn  btn-sm btn-info" value="{{__('common.search')}} " type="submit"/>
                        <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i>{{__('common.reload')}} </a>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>

            @if($modal)
            <div class="box">
                <div class="box-body">
                    <div class="col-md-12 text-right" style="padding: 0 20px;">
                        <a class="btn btn-sm  btn-primary pull-right ml-1" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" id="btn-print"> <i class="fa fa-print"></i>{{__('common.print')}} </a>
                        <a class="btn btn-sm  btn-success pull-right ml-1" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" > <i class="fa fa-download"></i>{{__('common.download')}}  </a>

                        <a class="btn btn-sm  btn-info ml-1" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=excel" > <i class="fa fa-file-excel-o"></i>{{__('common.excel')}}   </a>
                    </div>

                    <div class="col-lg-12" id="custom-print">

{{--                            @foreach($account_types as $key1=>$account_type)--}}
                            <div class="box">
                                <div class="box-header with-border">

                                    <h3 class="box-title" style="margin: 10px 0;"> {{__('common.'.strtolower($account_types->display_name))}} <span class="text-danger" style="font-size: 14px;">{{ $account_types->deleted_at == '' ? "" : " Deleted: ".date_string_format_with_time($account_types->deleted_at) }}</span> </h3>
                                <table class="table table-bordered" style="margin-bottom: 50px;">

                                    <tbody>

                                    <tr>
                                        <th style="width: 100px;">{{__('common.date')}}  </th>
                                        <th>{{__('common.transaction_code')}} </th>
                                        <th style="width: 250px;">{{__('common.particulars')}} </th>
{{--                                        <th class="">Payment Type</th>--}}
{{--                                        <th class="">Cash or Bank Account Name</th>--}}
{{--                                        <th> Supplier/Customer Name</th>--}}
                                        <th style="width: 250px !important;">{{__('common.remarks')}} </th>
                                        <th class="text-right">{{__('common.debit')}} </th>
                                        <th class="text-right">{{__('common.credit')}} </th>
                                        <th class="text-right">{{__('common.balance')}} </th>
                                        {{-- <th class="text-right">Balance </th> --}}
                                    </tr>

                                    @php
                                            $total_dr = 0.00;
                                            $total_cr = 0.00;
                                            $balance = 0.00;
                                    @endphp

                                    @if(isset($bf_balance) && $bf_balance !=0 )
                                        @php
                                            $total_dr += $bf_balance>0 ? $bf_balance : 0;
                                            $total_cr += $bf_balance<0 ? $bf_balance*(-1) : 0;
                                            $balance = $bf_balance;
                                        @endphp

                                        <tr>
                                            <td class="">{{ db_date_month_year_format($bf_date) }}</td>
                                            <td colspan=""></td>
                                            <td colspan="" style="width: 250px;">Balance B/F</td>
                                            <td class="" style="width: 250px  !important;">B/F</td>
                                            <td class="text-right ">{{ $bf_balance>0 ? create_money_format($bf_balance) : "-" }}</td>
                                            <td class="text-right ">{{ $bf_balance<0 ? create_money_format($bf_balance*(-1)) : "-" }}</td>
                                            <td class="text-right ">{{ create_money_format($balance) }}</td>
                                        </tr>
                                    @endif
                                @foreach($modal as $key2=>$value2)
                                        @php
                                                if($value2->transaction_type=='dr'){
                                                    $total_dr += $value2->td_amount;
                                                }else{
                                                    $total_cr += $value2->td_amount;
                                                }
                                            $balance = $value2->transaction_type=='dr' ? $balance+$value2->td_amount : $balance-$value2->td_amount;
                                            @endphp
                                            <tr>
                                                <td class="">{{ db_date_month_year_format($value2->date) }}</td>
                                                <td class="">
                                                    @if($value2->transaction_method=='Journal Entry')
                                                    <a href="{{ route('member.journal_entry.show', $value2->transaction_code) }}"> {{ $value2->transaction_code }}</a>
                                                    @else
                                                    <a href="{{ route('member.general_ledger.show', $value2->transaction_code) }}"> {{ $value2->transaction_code }} </a>
                                                    @endif
                                                </td>
                                                <td style="width: 250px !important;" class="">{{ $value2->against_account_name }}</td>
{{--                                                <td class="">{{ $value2->payment_type_name }}</td>--}}
{{--                                                <td class="">{{ $value2->title }}</td>--}}
{{--                                                <td class="">{{ $value2->sharer_name }}</td>--}}
                                                <td  style="width: 250px;">{{ $value2->remarks }}</td>
                                                <td class="text-right ">{{ $value2->transaction_type=="dr" ? create_money_format($value2->td_amount) : "-"}}</td>
                                                <td class="text-right ">{{ $value2->transaction_type=="cr" ? create_money_format($value2->td_amount) : "-"}}</td>
                                                <td class="text-right ">{{ create_money_format($balance) }}</td>
                                            </tr>
                                    @endforeach
                                    <tr>
                                        <th colspan="4" class="text-center">Balance C/d</th>
                                        <th class="text-right">{{ create_money_format($total_dr)}}</th>
                                        <th class="text-right">{{ create_money_format($total_cr) }}</th>
                                        <th class="text-right">{{ $balance>0 ? create_money_format($balance)." Dr" : create_money_format($balance*(-1))." Cr"}}</th>
                                    </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
{{--                            @endforeach--}}

                    </div>


                    <div class="col-md-12  text-right" style="padding: 0 20px;">
                        <a class="btn btn-sm  btn-primary pull-right ml-1" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" id="btn-print"> <i class="fa fa-print"></i> {{__('common.print')}} </a>
                        <a class="btn btn-sm  btn-success pull-right ml-1" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" > <i class="fa fa-download"></i> {{__('common.download')}} </a>

                        <a class="btn btn-sm  btn-info ml-1" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=excel" > <i class="fa fa-file-excel-o"></i> {{__('common.excel')}} </a>
                    </div>
                </div>
            </div>
                @endif
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


