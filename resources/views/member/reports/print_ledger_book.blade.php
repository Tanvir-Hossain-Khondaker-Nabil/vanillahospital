<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 05-Dec-19
 * Time: 6:28 PM
 */
?>

@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    @if($modal)
    <div style="overflow: hidden; clear: both;">
{{--        @foreach($account_types as $key1=>$account_type)--}}
            <div class="box" style="overflow: hidden;">
                <div class="box-header with-border">

                    <h3 class="box-title" style="margin: 10px 0;">Account Of {{ $account_types->display_name }} </h3>
                    <table class="table table-bordered" style="margin-bottom: 50px;">

                        <tbody>

                        <tr>
                            <th width="70px">Date</th>
                            <th >Particulars</th>
                            {{--                                        <th class="">Payment Type</th>--}}
                            {{--                                        <th class="">Cash or Bank Account Name</th>--}}
                            {{--                                        <th> Supplier/Customer Name</th>--}}
                            <th style="width: 200px;">Remarks</th>
                            <th class="text-right">DR. TK. </th>
                            <th class="text-right">CR. TK. </th>
                            <th class="text-right">Balance </th>
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
                                <td class=""  width="70px">{{ db_date_month_year_format($bf_date) }}</td>
                                <td style="width: 250px;" class="text-left">Balance B/F</td>
                                <td style="width: 250px;" class="text-left">B/F</td>
                                <td class="text-right ">{{ $bf_balance>0 ? create_money_format($bf_balance) : "" }}</td>
                                <td class="text-right ">{{ $bf_balance<0 ? create_money_format($bf_balance*(-1)) : "" }}</td>
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
                                    <td class=""  width="70px">{{ db_date_month_year_format($value2->date) }}</td>
                                    <td style="width: 250px;" class="text-left">{{ $value2->against_account_name }}</td>
                                    {{--                                                <td class="">{{ $value2->payment_type_name }}</td>--}}
                                    {{--                                                <td class="">{{ $value2->title }}</td>--}}
                                    {{--                                                <td class="">{{ $value2->sharer_name }}</td>--}}
                                    <td style="width: 250px;" class="text-left">{{ $value2->remarks }}</td>
                                    <td class="text-right ">{{ $value2->transaction_type=="dr" ? create_money_format($value2->td_amount) : ""}}</td>
                                    <td class="text-right ">{{ $value2->transaction_type=="cr" ? create_money_format($value2->td_amount) : ""}}</td>
                                    <td class="text-right ">{{ create_money_format($balance) }}</td>
                                </tr>


                        @endforeach
                        <tr>
                            <th colspan="3" class="text-center">Balance C/d</th>
                            <th class="text-right">{{ create_money_format($total_dr)}}</th>
                            <th class="text-right">{{ create_money_format($total_cr) }}</th>
                            <th class="text-right">{{ $balance>0 ? create_money_format($balance)." Dr" : create_money_format($balance*(-1))." Cr"}}</th>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
{{--        @endforeach--}}
    </div>
        @endif
</div>
</body>
</html>
