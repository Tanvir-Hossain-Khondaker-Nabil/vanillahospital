<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/6/2019
 * Time: 12:13 PM
 */
?>

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />

    <title>Daily Sheet Reports</title>
    <style>
        * { margin: 0; padding: 0; }
        body {
            font: 11px/1.4 Helvetica, Arial, sans-serif;
        }
        #page-wrap { width: 720px; margin: 0 auto; }

        /*table{*/
        /*    border: 0.3px solid #9c9c9c !important;*/
        /*}*/

        table {
            display: table;
            border-collapse: collapse;
            border-spacing: 0;
            color: #0a0a0a !important;
            width: 100% !important;

        }

        .table{
            border:  0.3px solid rgba(1, 1, 1, 0.32) !important;
        }
        .table tbody tr td, .table thead tr th, .table tbody tr th, .table thead tr td {
            font-size: 13px;
            padding: 3px !important;
            border: 0.3px solid rgba(1, 1, 1, 0.32) !important;
        }
        .table-border-padding{
            border:  0.3px solid rgba(1, 1, 1, 0.32) !important;
            padding: 3px !important;
        }
        .text-center{
            text-align: center !important;
        }
        .text-right{
            text-align: right !important;
        }

        #logo { text-align: right; width: 70px; height: 50px; overflow: hidden; }


        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }

            .page-break{
                page-break-after: always;
            }
        }
    </style>
</head>
<body>
<div id="page-wrap">

    @include('member.reports.company')
    <div style="width: 100%; text-align: center; margin-bottom: 20px;">
        <h3>{{ $report_date }}</h3>
        <h3>{{ $daily_sheet_title }}</h3>
    </div>
    <div style="width: 100%;">
        <table class="table" cellspacing="0" cellpadding="0" style="width: 100%; ">

            <tbody>
            <tr>
                <th colspan="4" class="text-center border-right-1"> ------- Credit -------</th>
            </tr>
            <tr>
                <th>Vo. No</th>
                <th>Particulars</th>
                {{--                                <th>L.P. No</th>--}}
                <th class="border-right-1 text-right">Taka</th>
{{--                <th class="border-right-1 text-right">Total Taka</th>--}}
            </tr>
            @if($lastTotalCredit>0)
                <tr>
                    <th colspan="1"></th>
                    <th colspan="1">B/F</th>
                    <td class="text-right">{{ create_money_format($lastTotalCredit) }}</td>
                </tr>
            @endif
            @php
                $total_credit = $last_transaction = $transactions_id = $transaction_details_id = $sale_print = 0 ;
                $last_item = $last_sharer = $last_account_name = '';
                $price_text = true;
            $use_sale_details = [];
            @endphp

            {{--            @foreach($cash_banks as $value)--}}
            {{--                @if($value['balance']>0 && !in_array($transaction_method, ["Purchases", "Sales", "bank"]) )--}}
            {{--                    <tr>--}}
            {{--                        <th colspan="1"></th>--}}
            {{--                        <th colspan="1">{{ $value['account_type_name'] }}</th>--}}
            {{--                        <th colspan="1">B/F</th>--}}
            {{--                        <td class="text-right">{{ create_money_format( $total_credit += $value['balance']) }}</td>--}}
            {{--                    </tr>--}}
            {{--                @endif--}}
            {{--            @endforeach--}}

            @if($cash_balance >0 && !in_array($transaction_method, ["Purchases", "Sales", "bank"]) )
                <tr>
                    <th colspan="1"></th>
                    <th colspan="1">Balance B/D</th>
                    <td class="text-right">{{ create_money_format( $total_credit += $cash_balance ) }}</td>
                </tr>
            @endif

            @foreach($credits as $key=>$value)
                {{--                @if($transaction_details_id != $value->transaction_details_id && $transactions_id != $value->transactions_id)--}}

                @php
                    $total = 0;
                    $transaction_details_id = $value->transaction_details_id;
                    $transactions_id = $value->transactions_id;
                    $account_name = $value->account_name;
                 //   $sharer_name = $value->sharer_name;
                    $credit = $value->td_amount;

                    if($value->item_name != $last_item || $value->item_name == null)
                     {
                        $sale_print = 0;
                        $price_text = true;
                     }


                     if( in_array($value->sales_details_id, $use_sale_details))
                    {
                        break;
                    }


                @endphp


                <tr>
                    <td>{{ $value->transactions_id }}</td>
                    <td width="600px">
                        @if($value->sale_id > 0 || ($value->item_name == $last_item && $value->item_name != null))
                            <div style="width: 150px; float: left;">{{ $value->item_name}} </div>
                            <div style="width: 350px; float: left; text-align: right;">
                                {{$value->sale_qty.$value->item_unit." X ".$value->sale_price}}  =
                                {{create_money_format($value->sale_total_price)}}</div>

                            @php
                                $price_text = false;
                                    $sale_print++;

                                array_push($use_sale_details, $value->sales_details_id);
                            @endphp

                        @else
                            {{  $value->account_name.($value->against_account_name ? "(".$value->against_account_name.")" : "") }}
                            <small> {!! $value->description ? "<br/>".$value->description : "" !!}<small>
                        @endif

                    </td>
                    {{-- <td>{{ $value->account_type_id }}</td>--}}


                    @if($value->sale_id > 0 || ($value->item_name == $last_item && $value->item_name != null))
                        @if($last_item!=$value->item_name)
                            @foreach($sales as $key=>$sale)
                                @if($sale->item_id==$value->item_id)
                                    @php $sale_item = true; @endphp
                                    @php
                                        $total_credit +=  $sale->sale_total_price;
                                    @endphp
                                    <td class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{ $sale->count }}" >
                                        {{ create_money_format($sale->sale_total_price) }} <br/>
                                        ({{ $sale->sale_qty." ".$sale->item->unit }})
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

                        @foreach($creditAmountByHead as $v)
                            {{--                                                {{ $v->account_name.$last_account_name  }}--}}
                            @if($v->account_name == $value->account_name && $v->account_name != $last_account_name)
{{--                                <td  class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{$v->transaction_count > 1 ? $v->transaction_count : ''}}">--}}
                                    @php
                                        $total_credit +=   $v->transaction_amount;
                                    @endphp
{{--                                    {{ create_money_format($v->transaction_amount) }}--}}
{{--                                </td>--}}
                            @endif
                        @endforeach
                    @endif
                </tr>

                @php
                    $last_item = $value->item_name;
                  //  $last_sharer = $value->sharer_name;
                    $last_account_name = $value->account_name;
                @endphp
                {{--                @endif--}}

            @endforeach

            <tr>
                <th colspan="2" class="text-right"> Total</th>
                <th colspan="1" class="text-right border-dual">{{ create_money_format($total_credit) }}</th>
            </tr>
            </tbody>
        </table>

        <div class="page-break"></div>


        @include('member.reports.company')
        <div style="width: 100%; text-align: center; margin-bottom: 20px;">
            <h3>{{ $report_date }}</h3>
            <h3>{{ $daily_sheet_title }}</h3>
        </div>

        <table  class="table" cellspacing="0" cellpadding="0" style="width: 100%; ">
            <tbody>
            <tr>
                <th colspan="3" class="text-center border-right-1"> ------- Debit -------</th>
            </tr>
            <tr>
                <th>Vo. No</th>
                <th>Particulars</th>
                <th>Taka</th>
{{--                <th class="border-right-1 text-right">Total Taka</th>--}}
            </tr>
            @if($lastTotalDebit>0)
                <tr>
                    <th colspan="1"></th>
                    <th colspan="2">B/F</th>
                    <td class="text-right">{{ create_money_format($lastTotalDebit) }}</td>
                </tr>
            @endif
            @php
                $total_debit = $last_transaction = $transactions_id = $transaction_details_id = $purchase_print = 0 ;
                $last_item = $last_sharer = $last_account_head = $d_head= '';
                $price_text = true;
            @endphp


            {{--            @foreach($cash_banks as $value)--}}
            {{--                @if($value['balance']<0 && !in_array($transaction_method, ["Purchases", "Sales", "bank"]))--}}
            {{--                    <tr>--}}
            {{--                        <th colspan="1"></th>--}}
            {{--                        <th colspan="1">{{ $value['account_type_name'] }}</th>--}}
            {{--                        <th colspan="1">B/F</th>--}}
            {{--                        <td class="text-right">{{ create_money_format($total_debit += $value['balance']*(-1)) }}</td>--}}
            {{--                    </tr>--}}
            {{--                @endif--}}
            {{--            @endforeach--}}


            @foreach($debits as $key=>$value)
                {{--                                @if($value->sale_id > 0 && ($value->account_name == "Accounts Receivable" || $value->account_name == "Cash"))--}}
                {{--                @if($transaction_details_id != $value->transaction_details_id && $transactions_id != $value->transactions_id)--}}

                @php
                    $total = 0;
                    $transaction_details_id = $value->transaction_details_id;
                    $transactions_id = $value->transactions_id;
                    $account_name = $value->account_name;
              //      $sharer_name = $value->sharer_name;
                    $debit = $value->td_amount;

                    if($value->item_name != $last_item || $value->item_name == null)
                     {
                        $purchase_print = 0;
                        $price_text = true;
                     }
                @endphp

                <tr>
                    <td>{{ $value->transactions_id }}</td>
                    <td width="600px">
                        @if($value->purchase_id > 0 || ($value->item_name == $last_item && $value->item_name != null))
                            <div style="width: 150px; float: left;">{{ $value->item_name}} </div>
                            <div style="width:350px; float: left; text-align: right;">
                                {{$value->purchase_qty.$value->item_unit." X ".$value->purchase_price}}  =
                                {{create_money_format($value->purchase_price*$value->purchase_qty)}}</div>

                            @php
                                $price_text = false;
                                    $purchase_print++;
                            @endphp

                        @else
                            {{  $value->account_name.($value->against_account_name ? "(".$value->against_account_name.")" : "") }}
                            <small> {!! $value->description ? "<br/>".$value->description : "" !!}<small>
                        @endif

                    </td>
                    {{-- <td>{{ $value->account_type_id }}</td>--}}


                    @if($value->purchase_id > 0 || ($value->item_name == $last_item && $value->item_name != null))
                        @if($last_item!=$value->item_name)
                            @foreach($purchases as $key=>$purchase)
                                @if($purchase->item_id==$value->item_id)
                                    @php $purchase_item = true; @endphp
                                    @php
                                        $total_debit +=  $purchase->purchase_total_price;
                                    @endphp
                                    <td class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{ $purchase->count }}" >
                                        {{ create_money_format($purchase->purchase_total_price) }} <br/>
                                        ({{ $purchase->purchase_qty." ".$purchase->item->unit }})
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

                        @foreach($debitAmountByHead as $v)
                            @if($v->account_name == $value->account_name && $v->account_name != $last_account_head)
{{--                                <td  class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{$v->transaction_count > 1 ? $v->transaction_count : ''}}">--}}
                                    @php
                                        $total_debit +=   $v->transaction_amount;
                                    @endphp
{{--                                    {{ create_money_format($v->transaction_amount) }}--}}
{{--                                </td>--}}
                                {{--                                                @elseif($v->transaction_count==1)--}}
                                {{--                                                    {{ create_money_format($value->transaction_amount) }}--}}
                            @endif
                        @endforeach
                    @endif


                </tr>

                @php
                    $last_item = $value->item_name;
                 //   $last_sharer = $value->sharer_name;
                    $last_account_head = $value->account_name;
                @endphp
                {{--                @endif--}}

            @endforeach
            @php
                $balance_CD = ($total_debit-$total_credit)*(-1);
            @endphp
            @if($transaction_method == "cash")
                <tr>
                    <th colspan="1" > </th>
                    <th colspan="1" > Balance C/D</th>
                    <td colspan="1" class="text-right border-dual">{{ create_money_format($balance_CD) }}</td>
                </tr>
            @endif
            <tr>
                <th colspan="2" class="text-right"> Total</th>
                <th colspan="1" class="text-right border-dual">{{ create_money_format($total_debit+$balance_CD) }}</th>
            </tr>
            </tbody>

        </table>
    </div>
</div>
</body>
</html>

