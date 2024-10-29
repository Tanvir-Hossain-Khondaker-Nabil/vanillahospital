<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/29/2020
 * Time: 2:30 PM
 */
?>

@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style=" text-align: center!important;">

        <table class="table table-responsive table-striped table-bordered ">
            <thead class="text-center">
            <tr>
                <th colspan="5">General Ledger Transaction Details</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <th>Transaction Code</th>
                <th>Transaction Date</th>
                <th>Transaction Method</th>
                <th colspan="1"> Entry By</th>
            </tr>
            <tr>
                <td>{{ $transaction->transaction_code }}</td>
                <td>{{ date_month_year_format($transaction->date) }}</td>
                <td>@if($transaction->transaction_method == "Expense")
                        {{ "Payment/ Debit " }}
                        {{--                                        @elseif($general_ledger['method'] == "Income")--}}
                        {{--                                             {{ "Income" }}--}}
                    @else
                        {{ "Received/ Credit" }}
                    @endif
                    Voucher
                </td>
                <td class="text-capitalize">{{ $transaction->created_user->full_name }}</td>
            </tr>

            </tbody>
        </table>
        <br/>
        <table class="table table-responsive table-bordered margin-top-30" >
            <tbody>
            <tr>
                <th>Date</th>
                <th>Account Code</th>
                <th>Account Name</th>
                <th class="text-center">Dr</th>
                <th class="text-center">Cr</th>
                <th>Description</th>
            </tr>

            @php
                $total_debit = 0;
                $total_credit = 0;

            @endphp

            @foreach($transaction->transaction_details as $key => $value)

                <tr>
                    <td>{{  db_date_month_year_format($value->date) }}</td>
                    <td>{{ format_number_digit($value->account_type->account_code)  }}</td>
                    <td>{{   $value->account_type->display_name }}</td>
                    <td  class="text-right">{{ $value->transaction_type == 'dr' ? create_money_format( $value->amount ) : "" }}</td>
                    <td class="text-right">{{ $value->transaction_type == 'cr' ?  create_money_format( $value->amount) : ""  }}</td>
                    <td>{{ $value->description }}</td>
                </tr>
                @php
                    $total_debit += $value->transaction_type == 'dr' ? $value->amount : 0;
                    $total_credit += $value->transaction_type == 'cr' ? $value->amount : 0;
                @endphp

            @endforeach

            <tr>
                <th colspan="3" > Total </th>
                <th class="text-right">{{ create_money_format($total_debit) }} <hr class="double-line" /><hr class="double-line" /> </th>
                <th class="text-right">{{ create_money_format($total_credit) }} <hr class="double-line" /><hr class="double-line" /> </th>
                <th></th>
            </tr>

            <tr>
                <th colspan="1" > Notation: </th>
                <td colspan="5"> {{ $transaction->notation }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

