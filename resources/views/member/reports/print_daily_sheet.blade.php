<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/6/2019
 * Time: 12:13 PM
 */
?>


@include('member.reports.print_head')

<style type="text/css">
    .table tbody tr td, .table thead tr th, .table tbody tr th, .table thead tr td {
        font-size: 11px;
    }
</style>
<body>
<div id="page-wrap">

    @include('member.reports.company')
    <div style="width: 100%; text-align: center; margin-bottom: 20px;">
        <h3>{{ $report_date }}</h3>
        <h3>{{ $daily_sheet_title }}</h3>
    </div>

    <div style="width: 100%; display: flex; flex-wrap: nowrap;">
        <div  style="width: 50%; float: left; position: relative; display: flex;" >
            <table class="table table-striped" id="dataTable">

                <tbody>
                <tr>
                    <th colspan="3" class="text-center border-right-1"> ------- Credit -------</th>
                </tr>
                <tr>
                    <th>Vo. No</th>
                    <th class="text-left">Particulars</th>
                    {{--                                <th>L.P. No</th>--}}
                    <th class="border-right-1 text-right">Taka</th>
{{--                    <th class="border-right-1 text-right">Total Taka</th>--}}
                </tr>

                @php
                    $total_credit = $last_transaction = $transactions_id = $transaction_details_id = $sale_print = 0 ;
                    $last_item = $last_sharer = $last_account_head = '';
                    $price_text = true;
                    $use_sale_details = [];
                @endphp

{{--                @foreach($cash_banks as $value)--}}
{{--                    @if($value['balance']>0 && !in_array($transaction_method, ["Purchases", "Sales", "bank"]) )--}}
{{--                        <tr>--}}
{{--                            <th colspan="1"></th>--}}
{{--                            <th colspan="1">{{ $value['account_type_name'] }}</th>--}}
{{--                            <th colspan="1">B/F</th>--}}
{{--                            <td class="text-right">{{ create_money_format( $total_credit += $value['balance']) }}</td>--}}
{{--                        </tr>--}}
{{--                    @endif--}}
{{--                @endforeach--}}


                @if($cash_balance >0 && !in_array($transaction_method, ["Purchases", "Sales", "bank"]) )
                    <tr>
                        <th colspan="1"></th>
                        <th colspan="1">Balance B/D</th>
                        <td class="text-right">{{ create_money_format( $total_credit += $cash_balance ) }}</td>
                    </tr>
                @endif

                @foreach($credits as $key=>$value)
{{--                    @if($transaction_details_id != $value->transaction_details_id && $transactions_id != $value->transactions_id)--}}

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

                            }
                        @endphp

                        <tr>
                            <td>{{ $value->transactions_id }}</td>
                            <td  class="text-left">
                                @if( ($value->sale_id > 0 || ($value->item_name == $last_item && $value->item_name != null)) &&  $account_name== "Sales")
                                    <div style="float: left;">{{ $value->item_name}} </div>
                                    <div style="float: left; text-align: right;">
                                        {{$value->sale_qty.$value->item_unit." X ".$value->sale_price}}  =
                                        {{create_money_format($value->sale_total_price)}}</div>

                                    @php
                                        $price_text = false;
                                            $sale_print++;
                                        array_push($use_sale_details, $value->sales_details_id);
                                    @endphp

                                @else
                                    {{  $value->account_name.($value->against_account_name ? "(".$value->against_account_name.")" : "") }}
                                    <small>  {!! $value->description ? "<br/>".$value->description : "" !!} </small>
                                @endif

                            </td>
                            {{-- <td>{{ $value->account_type_id }}</td>--}}


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
{{--                                            <td class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{ $sale->count }}" >--}}
{{--                                                {{ create_money_format($sale->sale_total_price) }} <br/>--}}
{{--                                            </td>--}}
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
{{--                                            <td  class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{$v->transaction_count > 1 ? $v->transaction_count : ''}}">--}}
                                                @php
                                                    $total_credit +=   $v->transaction_amount;
                                                @endphp
{{--                                                {{ create_money_format($v->transaction_amount) }}--}}
{{--                                            </td>--}}
                                        @endif
                                    @endforeach
                                @else
                                    @foreach($creditAmountByHeadByItem as $v)
                                        @if($v->account_name == $value->account_name && $v->account_name != $last_account_head)
{{--                                            <td  class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{$v->transaction_count > 1 ? $v->transaction_count : ''}}">--}}
                                                @php
                                                    $total_credit +=   $v->transaction_amount;
                                                @endphp
{{--                                                {{ create_money_format($v->transaction_amount) }}--}}
{{--                                            </td>--}}
                                        @endif
                                    @endforeach

                                @endif
                            @endif
                        </tr>

                        @php
                            $last_item = $value->item_name;
                          //  $last_sharer = $value->sharer_name;
                            $last_account_head = $value->account_name;
                        @endphp
{{--                    @endif--}}

                @endforeach

                <tr>
                    <th colspan="2" class="text-right">  Total</th>
                    <th colspan="1" class="text-right border-dual">{{ create_money_format($total_credit) }}</th>
                </tr>
                </tbody>
            </table>
        </div>

        <div style="width: 50%; float: right; position: relative; display: inline-block; overflow: hidden;">
            <table class="table table-striped" id="dataTable">

                <tbody>
                <tr>
                    <th colspan="3" class="text-center border-right-1"> ------- Debit -------</th>
                </tr>
                <tr>
                    <th>Vo. No</th>
                    <th class="text-left">Particulars</th>
                    <th>Taka</th>
{{--                    <th class="border-right-1 text-right">Total Taka</th>--}}
                </tr>
                @php
                    $total_debit = $last_transaction = $last_transaction_id = $transactions_id = $transaction_details_id = $sale_print = 0 ;
                    $last_item = $last_sharer = $last_account_head = $d_head= '';
                    $price_text = true;
                @endphp

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
                        <td class="text-left">
                            @if( ($value->purchase_id > 0 || ($value->item_name == $last_item && $value->item_name != null)) &&  $account_name == "Purchase"  )
                                <div style="float: left;">{{ $value->item_name}} </div>
                                <div style="float: left; text-align: right;">
                                    {{$value->purchase_qty.$value->item_unit." X ".$value->purchase_price}}  =
                                    {{create_money_format($value->purchase_total_price)}}</div>

                                @php
                                    $price_text = false;
                                        $purchase_print++;
                                @endphp

                            @else
                                {{  $value->account_name.($value->against_account_name?"(".$value->against_account_name.")":"") }}
                               <small> {!! $value->description ? "<br/>".$value->description : "" !!}<small>
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
{{--                                        <td class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{ $purchase->count }}" >--}}
{{--                                            {{ create_money_format($purchase->purchase_total_price) }} <br/>--}}

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
{{--                                        <td  class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{$v->transaction_count > 1 ? $v->transaction_count : ''}}">--}}
                                            @php
                                                $total_debit +=   $v->transaction_amount;
                                            @endphp
{{--                                            {{ create_money_format($v->transaction_amount) }}--}}
{{--                                        </td>--}}
                                    @endif
                                @endforeach
                            @else
                                @foreach($debitAmountByHead as $v)
                                    @if($v->account_name == $value->account_name && $v->account_name != $last_account_head)
{{--                                        <td  class="border-right-1 text-right" style="vertical-align: middle;" rowspan="{{$v->transaction_count > 1 ? $v->transaction_count : ''}}">--}}
                                            @php
                                                $total_debit +=   $v->transaction_amount;
                                            @endphp
{{--                                            {{ create_money_format($v->transaction_amount) }}--}}
{{--                                        </td>--}}
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
                        <th colspan="1"> Total</th>
                        <th colspan="1" class="text-right border-dual">{{ create_money_format($total_debit) }}</th>
                    </tr>
                @endif


                @if($transaction_method == "cash")
                <tr>
                    <th colspan="1" > </th>
                    <th colspan="1" > Balance C/D</th>
                    <td colspan="1" class="text-right border-dual">{{ create_money_format($balance_CD) }}</td>
                </tr>
                <tr>
                    <th colspan="2" class="text-right"> Grand Total</th>
                    <th colspan="1" class="text-right border-dual">{{ create_money_format($total_debit+$balance_CD) }}</th>
                </tr>
                @endif
                </tbody>
            </table>
        </div>


    </div>
</div>
</body>
</html>

