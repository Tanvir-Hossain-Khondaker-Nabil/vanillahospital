<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/2/2019
 * Time: 3:34 PM
 */
?>

@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="overflow: hidden; clear: both;">
        <table class="table table-striped" id="dataTable">

            <tbody>
            <tr>
                <th class="border-right-1 ">#SL</th>
                <th class="border-right-1 " >Head of Accounts </th>
                <th class="border-right-1 ">LF. No. </th>
                <th class="border-right-1 text-right">DR. TK.</th>
                <th class="border-right-1 text-right">CR. TK.</th>
                {{--                                <th class="border-right-1  text-right">Balance</th>--}}
            </tr>
            @php
                $total_dr = 0;
                $total_cr = 0;
                $cr_amount = '-';
                $dr_amount = '-';
                        $i = 0;
            @endphp
            @php
                // This Cash in Hand will be Credit.
                 $last_cr = $total_cr = $cash_balance >= 0 ? $cash_balance : 0;
                 $last_dr = $total_dr = $cash_balance < 0 ? $cash_balance : 0;
            @endphp
            <tr>
                <td class="border-right-1">{{ $i }}</td>
                <th class="border-right-1">Cash In Hand({{ $cash_date }})</th>
                <th class="border-right-1"> 4 </th>
                <th class="border-right-1 text-right">{{ $total_dr > 0 ? create_money_format($total_dr) : '' }}</th>
                <th class="border-right-1 text-right">{{ $total_cr > 0 ? create_money_format($total_cr) : '' }}</th>
                {{--                                <th class="border-right-1 text-right">{{ create_money_format($total_cr-$total_dr) }}</th>--}}
            </tr>
            @foreach($account_types as $key1=>$account_type)
                @php
                    $cr_amount = '';
                    $dr_amount = '';
                    $balance = 0.00;
                @endphp

                @foreach($modal as $key2=>$value2)

                    @if($value2->id == $account_type->id)

                        @php
                            $dr_amount = $value2->transaction_type=='dr' ? create_money_format($value2->total_amount): $dr_amount;
                            $cr_amount = $value2->transaction_type=='cr' ? create_money_format($value2->total_amount): $cr_amount;
                            $balance = $value2->transaction_type=='dr' ? $balance+$value2->total_amount : $balance-$value2->total_amount;

                                /*$total_dr += $balance<0 ? $balance*(-1) : 0;
                               $total_cr += $balance>0 ? $balance : 0; */
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
                    @endphp

                    <tr>
                        <td class="border-right-1 {{$class}}">{{ $i = $i+1 }}</td>
                        <td class="border-right-1 text-left {{$class}}">{{ $account_type->display_name }}</td>
                        <th class="border-right-1 {{$class}}">{{ $account_type->id }}</th>
                        <td class="border-right-1 text-right {{$class}}">{{ $balance>0 ? create_money_format($balance) : "" }}</td>
                        <td class="border-right-1 text-right {{$class}}">{{ $balance<0 ? create_money_format($balance*(-1)) : "" }}</td>
                    {{--                                 {{--                                    <td class="border-right-1 text-right {{$class}}">{{create_money_format($balance)}}</td>--}}
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
                                $last_dr += $balance>0 ? $val['balance']+$balance : $balance+$val['balance'];
                                $last_cr += $balance<0 ? 0 : 0;
                            @endphp
                            <tr>
                                <td class="border-right-1 {{$class}}">{{ $i = $i+1 }}</td>
                                <td class="border-right-1 {{$class}}">{{ $account_type->display_name }}</td>
                                <th class="border-right-1 {{$class}}">{{ $account_type->id }}</th>
                                <td class="border-right-1 text-right {{$class}}">{{ $balance>0 ? create_money_format($val['balance']+$balance) :create_money_format($balance+$val['balance'])}}</td>
                                <td class="border-right-1 text-right {{$class}}">{{ $balance<0 ? '' : "" }}</td>
                                {{--                                            <td class="border-right-1 text-right {{$class}}">{{ $balance<0 ? create_money_format($balance*(-1)) : "" }}</td>--}}
                                {{--                                            <td class="border-right-1 text-right {{$class}}">{{create_money_format($balance)}}</td>--}}
                            </tr>
                        @endif
                    @endif
                @endforeach

            @endforeach

            <tr>
                <th colspan="3">Total</th>
                <th class="border-right-1 text-right">{{ create_money_format($total_dr)}}</th>
                <th class="border-right-1 text-right">{{ create_money_format($total_cr) }}</th>
                {{--                                <th class="border-right-1 text-right">{{ create_money_format($total_cr-$total_dr) }}</th>--}}
            </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

