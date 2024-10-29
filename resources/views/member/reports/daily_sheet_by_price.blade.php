<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 19-Jan-20
 * Time: 6:16 PM
 *
 *
 *
 *  DAILY SHEET DEVELOP BY TOTAL Price to Per price conversion Rate.
 *
 *  ONLY TOTAL PRICE COUNTED
 */


 $route = \Auth::user()->can(['member.report.daily_sheet']) ? route('member.report.daily_sheet'): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Sheets',
        'href' => $route,
    ],
    [
        'name' => 'Daily Sheet Report',
    ],
];

$data['data'] = [
    'name' => 'Daily Sheet Report',
    'title'=> 'Daily Sheet Report',
    'heading' => 'Daily Sheet Report',
];

?>
@extends('layouts.back-end.master', $data)

@push('styles')
    <style>
        .table {
            margin-bottom: 0;
        }

        .table tr td, .table tr th{
            border: 1px solid #d8d8d8 !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Search</h3>
                </div>

            {!! Form::open(['route' => 'member.report.daily_sheet','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3">
                            <label> Transaction Type </label>
                            <select class="form-control" name="transaction_method">
                                <option value=""> Select </option>
                                <option value="cash"> Cash</option>
                                {{--                                <option value="due"> Due Receipt</option>--}}
                                <option value="bank">Bank </option>
                                <option value="Purchases">Purchase </option>
                                <option value="Sales">Sale </option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label> Date </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        {{--                        <div class="col-md-3">--}}
                        {{--                            <label> To Date</label>--}}
                        {{--                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>--}}
                        {{--                        </div>--}}
                        <div class="col-md-3 margin-top-23">
                            <label></label>
                            <input class="btn btn-info" value="Search" type="submit"/>
                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>

                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                {{--<div class="box-body">--}}

                {{----}}
                {{--</div>--}}

                {!! Form::close() !!}
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Daily Sheet Report {{ db_date_month_year_format($report_date) }}</h3>
                    <a class="btn btn-sm  btn-primary pull-right" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=print" id="btn-print"> <i class="fa fa-print"></i> Print </a>
                    <a class="btn btn-sm  btn-primary pull-right ml-5" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=download" > <i class="fa fa-print"></i> Download </a>
                    <a class="btn btn-sm  btn-success pull-right btn-print" href="{{ route(Route::current()->getName()) == $full_url ? route(Route::current()->getName())."?" : $full_url.'&' }}type=single_print"> <i class="fa fa-print"></i> Single Page Print</a>
                </div>

                <div class="box-body">
                    <div class="col-lg-6" style="padding-left: 0;padding-right: 0;">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th colspan="4" class="text-center border-right-1"> ------- Credit -------</th>
                            </tr>
                            <tr>
                                <th>Vo. No</th>
                                <th>Particulars</th>
                                <th>Taka</th>
                                <th class="border-right-1 text-right">Total Taka</th>
                            </tr>
                            @php
                                $total_credit = $last_transaction = $last_transaction_id = $transactions_id = $transaction_details_id = $sale_print = 0 ;
                                $last_item = $last_sharer = $last_account_head = $d_head= '';
                                $price_text = true;
                                $use_sale_details = [];
                            @endphp

                            @if($cash_balance >0 && !in_array($transaction_method, ["Purchases", "Sales", "bank"]) )
                                <tr>
                                    <th colspan="1"></th>
                                    <th colspan="2">Balance B/D</th>
                                    <td class="text-right">{{ create_money_format( $total_credit += $cash_balance ) }}</td>
                                </tr>
                            @endif


                            @foreach($credits as $key=>$value)

                                @php
                                    $total = 0;
                                    $transaction_details_id = $value->transaction_details_id;
                                    $transactions_id = $value->transactions_id;
                                    $account_name = $value->account_name;
                                    //$sharer_name = $value->sharer_name;
                                    $credit = $value->td_amount;

                                    if($value->item_name != $last_item || $value->item_name == null)
                                     {
                                        $sale_print = 0;
                                        $price_text = true;
                                     }

                                    if(in_array($value->sales_details_id, $use_sale_details)){

                                    }


                                @endphp

                                <tr>
                                    <td>{{ $value->transactions_id }}</td>
                                    <td width="500px">
                                        @if( ($value->sale_id > 0 || ($value->item_name == $last_item && $value->item_name != null)) &&  $account_name== "Sales")
                                            <div style="width: 150px; float: left;">{{ $value->item_name}} </div>
                                            <div style="width: 250px; float: left; text-align: right;">
                                                {{$value->sale_qty.$value->item_unit." X ".$value->sale_price}}  =
                                                {{create_money_format($value->sale_total_price)}}</div>

                                            @php
                                                $price_text = false;
                                                $sale_print++;
                                                array_push($use_sale_details, $value->sales_details_id);

                                            @endphp

                                        @else
                                            {{  $value->account_name.($value->against_account_name?"(".$value->against_account_name.")":"") }}
                                        @endif

                                    </td>



                                    @if( ($value->sale_id > 0 || ($value->item_name == $last_item && $value->item_name != null)) && $account_name == "Sales" )
                                        @if($last_item!=$value->item_name)
                                            @foreach($sales as $key=>$sale)
                                                @if($sale->item_id==$value->item_id)
                                                    @php $sale_item = true; @endphp
                                                    @php
                                                        $total_credit +=  $sale->sale_total_price;
                                                    @endphp
                                                    <td class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{ $sale->count }}" >
                                                        {{ create_money_format($sale->sale_total_price) }} <br/>
                                                        ({{ create_float_format($sale->sale_qty)." ".$sale->item->unit }})
                                                    </td>
                                                    <td class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{ $sale->count }}" >
                                                        {{ create_money_format($sale->sale_total_price) }} <br/>

                                                    </td>
                                                @else
                                                    @php $sale_item = false; @endphp
                                                @endif
                                            @endforeach
                                        @endif
                                    @else

                                        <td  class="border-right-1 text-right" >
                                            {{ create_money_format($value->td_amount) }}
                                        </td>

                                        @if($value->account_name != "Sales")
                                            @foreach($creditAmountByHead as $v)
                                                @if($v->account_name == $value->account_name && $v->account_name != $last_account_head)
                                                    <td  class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{$v->transaction_count > 1 ? $v->transaction_count : ''}}">
                                                        @php
                                                            $total_credit +=   $v->transaction_amount;
                                                        @endphp
                                                        {{ create_money_format($v->transaction_amount) }}
                                                    </td>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach($creditAmountByHeadByItem as $v)
                                                @if($v->account_name == $value->account_name && $v->account_name != $last_account_head)
                                                    <td  class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{$v->transaction_count > 1 ? $v->transaction_count : ''}}">
                                                        @php
                                                            $total_credit +=   $v->transaction_amount;
                                                        @endphp
                                                        {{ create_money_format($v->transaction_amount) }}
                                                    </td>
                                                @endif
                                            @endforeach

                                        @endif
                                    @endif
                                </tr>

                                @php
                                    $last_item = $value->item_name;
                                 //   $last_sharer = $value->sharer_name;
                                    $last_account_head = $value->account_name;
                                @endphp

                                {{--                                @endif--}}
                                @php
                                    $last_transaction_id = $transactions_id;
                                @endphp

                            @endforeach
                            <tr>
                                <th colspan="3" class="text-right"> Grand Total</th>
                                <th colspan="1" class="text-right border-dual">{{ create_money_format($total_credit) }}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-6" style="padding-left: 0;padding-right: 0;">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th colspan="4" class="text-center border-right-1"> ------- Debit -------</th>
                            </tr>
                            <tr>
                                <th>Vo. No</th>
                                <th>Particulars</th>
                                <th>Taka</th>
                                <th class="border-right-1 text-right">Total Taka</th>
                            </tr>
                            @php
                                $total_debit = $last_transaction = $last_transaction_id = $transactions_id = $transaction_details_id = $sale_print = 0 ;
                                $last_item = $last_sharer = $last_account_head = $d_head= '';
                                $price_text = true;
                            @endphp

                            {{--                            @foreach($cash_banks as $value)--}}
                            {{--                                @if($value['balance']<0 && !in_array($transaction_method, ["Purchases", "Sales", "bank"]))--}}
                            {{--                                    <tr>--}}
                            {{--                                        <th colspan="1"></th>--}}
                            {{--                                        <th colspan="1">{{ $value['account_type_name'] }}</th>--}}
                            {{--                                        <th colspan="1">B/F</th>--}}
                            {{--                                        <td class="text-right">{{ create_money_format($total_debit += $value['balance']*(-1)) }}</td>--}}
                            {{--                                    </tr>--}}
                            {{--                                @endif--}}
                            {{--                            @endforeach--}}

                            @foreach($debits as $key=>$value)
                                {{--                                @if($value->sale_id > 0 && ($value->account_name == "Accounts Receivable" || $value->account_name == "Cash"))--}}
                                {{--                                @if($transaction_details_id != $value->transaction_details_id && $transactions_id != $value->transactions_id)--}}

                                @php
                                    $total = 0;
                                    $transaction_details_id = $value->transaction_details_id;
                                    $transactions_id = $value->transactions_id;
                                    $account_name = $value->account_name;
                                    //$sharer_name = $value->sharer_name;
                                    $debit = $value->td_amount;

                                    if($value->item_name != $last_item || $value->item_name == null)
                                     {
                                        $purchase_print = 0;
                                        $price_text = true;
                                     }
                                @endphp

                                <tr>
                                    <td>{{ $value->transactions_id }}</td>
                                    <td width="500px">
                                        @if( ($value->purchase_id > 0 || ($value->item_name == $last_item && $value->item_name != null)) &&  $account_name == "Purchase"  )
                                            <div style="width: 150px; float: left;">{{ $value->item_name}} </div>
                                            <div style="width: 250px; float: left; text-align: right;">
                                                {{$value->purchase_qty.$value->item_unit." X ".$value->purchase_price}}  =
                                                {{create_money_format($value->purchase_total_price)}}</div>

                                            @php
                                                $price_text = false;
                                                    $purchase_print++;
                                            @endphp

                                        @else
                                            {{  $value->account_name.($value->against_account_name?"(".$value->against_account_name.")":"") }}
                                        @endif

                                    </td>
                                    {{-- <td>{{ $value->account_type_id }}</td>--}}


                                    @if( ($value->purchase_id > 0 || ($value->item_name == $last_item && $value->item_name != null) ) &&  $account_name == "Purchase")
                                        @if($last_item!=$value->item_name)
                                            @foreach($purchases as $key=>$purchase)
                                                @if($purchase->item_id==$value->item_id)
                                                    @php $purchase_item = true; @endphp
                                                    @php
                                                        $total_debit +=  $purchase->purchase_total_price;
                                                    @endphp
                                                    <td class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{ $purchase->count }}" >
                                                        {{ create_money_format($purchase->purchase_total_price) }} <br/>
                                                        ({{ create_float_format($purchase->purchase_qty)." ".$purchase->item->unit }})
                                                    </td>
                                                    <td class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{ $purchase->count }}" >
                                                        {{ create_money_format($purchase->purchase_total_price) }} <br/>

                                                    </td>
                                                @else
                                                    @php $purchase_item = false; @endphp
                                                @endif
                                            @endforeach
                                        @endif
                                    @else

                                        <td  class="border-right-1 text-right" >
                                            {{ create_money_format($value->td_amount) }}
                                        </td>

                                        @if( $value->account_name == "Purchase")

                                            @foreach($debitAmountByHeadByItem as $v)
                                                @if($v->account_name == $value->account_name && $v->account_name != $last_account_head)
                                                    <td  class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{$v->transaction_count > 1 ? $v->transaction_count : ''}}">
                                                        @php
                                                            $total_debit +=   $v->transaction_amount;
                                                        @endphp
                                                        {{ create_money_format($v->transaction_amount) }}
                                                    </td>
                                                @endif
                                            @endforeach
                                        @else
                                            @foreach($debitAmountByHead as $v)
                                                @if($v->account_name == $value->account_name && $v->account_name != $last_account_head)
                                                    <td  class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{$v->transaction_count > 1 ? $v->transaction_count : ''}}">
                                                        @php
                                                            $total_debit +=   $v->transaction_amount;
                                                        @endphp
                                                        {{ create_money_format($v->transaction_amount) }}
                                                    </td>
                                                @endif
                                            @endforeach
                                        @endif
                                    @endif
                                </tr>

                                @php
                                    $last_item = $value->item_name;
                                 //   $last_sharer = $value->sharer_name;
                                    $last_account_head = $value->account_name;
                                @endphp

                                {{--                                @endif--}}
                                @php
                                    $last_transaction_id = $transactions_id;
                                @endphp

                            @endforeach
                            @php
                                $balance_CD = ($total_debit-$total_credit)*(-1);
                            @endphp
                            @if($total_debit>0)
                                <tr>
                                    <th colspan="1" > </th>
                                    <th colspan="2"> Total</th>
                                    <th colspan="1" class="text-right border-dual">{{ create_money_format($total_debit) }}</th>
                                </tr>
                            @endif
                            <tr>
                                <th colspan="1" > </th>
                                <th colspan="2" > Balance C/D</th>
                                <th colspan="1" class="text-right border-dual"> <i>{{ create_money_format($balance_CD) }} </i></th>
                            </tr>
                            <tr>
                                <th colspan="3" class="text-right"> Grand Total</th>
                                <th colspan="1" class="text-right border-dual">{{ create_money_format($total_debit+$balance_CD) }}</th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['developer','super-admin']))
                    <div class="col-lg-12">
                        <div class=" text-right " style="padding-top: 20px;">
                            {!! Form::open(['route' => 'admin.ledger_book.update_daily_cash_balance', 'method' => 'POST', 'role'=>'form' ]) !!}
                            <input type="hidden" value="{{ $balance_CD }}" name="balance">
                            <input type="hidden" value="{{ $report_date }}" name="date">
                            <input type="submit" value="Cash Update" class="btn btn-success btn-sm"/>

                            {!! Form::close() !!}
                        </div>
                    </div>
                    @endif


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
