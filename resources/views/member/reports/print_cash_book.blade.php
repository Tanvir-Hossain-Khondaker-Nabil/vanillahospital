<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/17/2019
 * Time: 1:02 PM
 */
?>

@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="width: 100%; display: flex; flex-wrap: nowrap;">
        <div style="width: 100%;  position: relative; display: block;">

            <table >
                <thead>
                <tr>
                    <th style="border: 1px solid #333 !important;" colspan="7" class="text-center border-right-1 table-border-padding"> -------- Received --------</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #333;">Date</th>
                    <th style="border: 1px solid #333;">Vr. No</th>
                    <th style="border: 1px solid #333;">Received</th>
                    <th style="border: 1px solid #333;">Folio</th>
                    <th class="border-right-1 text-right">Cash </th>
                    <th class="border-right-1 text-right ">Bank</th>
                    <th class="border-right-1 text-right">Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $total_income_cash = 0;
                    $total_income_bank = 0;
                    $total_income = 0;
                @endphp
                @foreach($incomes as $key=>$value)
                    <tr>
                        <td style="border: 1px solid #333;">{{ db_date_month_year_format($value->date) }}</td>
                        <td style="border: 1px solid #333;">{{ $value->transaction_id }}</td>
                        <td style="border: 1px solid #333;">{{ $value->account_name }}</td>
                        <td style="border: 1px solid #333;">{{ $value->full_name }}</td>
                        <td class="border-right-1 text-right table-border-padding">
                            {{ create_money_format($value->cash_amount) }}
                        </td>
                        <td class="border-right-1 text-right table-border-padding">
                            {{ create_money_format($value->bank_amount) }}
                        </td>
                        <td class="border-right-1 text-right table-border-padding">
                            {{ create_money_format($value->transaction_amount) }}
                        </td>
                    </tr>
                    @php
                        $total_income_cash += $value->cash_amount;
                        $total_income_bank += $value->bank_amount;
                        $total_income += $value->transaction_amount;
                    @endphp
                @endforeach
                <tr>
                    <td colspan="4" class="border-right-1 text-right table-border-padding"> Total</td>
                    <td class="border-right-1 text-right table-border-padding">{{ create_money_format($total_income_cash) }}</td>
                    <td class="border-right-1 text-right table-border-padding">{{ create_money_format($total_income_bank) }}</td>
                    <td class="border-right-1 text-right table-border-padding">{{ create_money_format($total_income) }}</td>
                </tr>
                </tbody>
            </table>

        </div>

        <div style="width: 100%; position: relative;  display: block; overflow: hidden;">
            <table>
                <thead>
                <tr>
                    <th style="border: 1px solid #333 !important;" colspan="7" class="text-center border-right-1 table-border-padding"> ------- Payment -------</th>
                </tr>
                <tr>
                    <th style="border: 1px solid #333;">Date</th>
                    <th style="border: 1px solid #333;">Vr. No</th>
                    <th style="border: 1px solid #333;">Payment</th>
                    <th style="border: 1px solid #333;">Folio</th>
                    <th class="border-right-1 text-right table-border-padding">Cash </th>
                    <th class="border-right-1 text-right table-border-padding">Bank</th>
                    <th class="border-right-1 text-right table-border-padding">Total</th>
                </tr>
                </thead>
                <tbody>
                @php
                    $total_ex_cash = 0;
                    $total_ex_bank = 0;
                    $total_expense = 0;
                @endphp
                @foreach($expenses as $key=>$value)
                    <tr>
                        <td style="border: 1px solid #333;">{{ db_date_month_year_format($value->date) }}</td>
                        <td style="border: 1px solid #333;">{{ $value->transaction_id }}</td>
                        <td style="border: 1px solid #333;">{{ $value->account_name }}</td>
                        <td style="border: 1px solid #333;">{{ $value->full_name }}</td>
                        <td class="border-right-1 text-right table-border-padding">
                            {{ create_money_format($value->cash_amount) }}
                        </td>
                        <td class="border-right-1 text-right table-border-padding">
                            {{ create_money_format($value->bank_amount) }}
                        </td>
                        <td class="border-right-1 text-right table-border-padding">
                            {{ create_money_format($value->transaction_amount) }}
                        </td>
                    </tr>
                    @php
                        $total_ex_cash += $value->cash_amount;
                        $total_ex_bank += $value->bank_amount;
                        $total_expense += $value->transaction_amount;
                    @endphp
                @endforeach
                    <tr>
                        <td colspan="4" class="border-right-1 text-right table-border-padding"> Total</td>
                        <td class="border-right-1 text-right table-border-padding">{{ create_money_format($total_ex_cash) }}</td>
                        <td class="border-right-1 text-right table-border-padding">{{ create_money_format($total_ex_bank) }}</td>
                        <td class="border-right-1 text-right table-border-padding">{{ create_money_format($total_expense) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>





