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

    <div style=" text-align: left!important;">

        <table class="bill-info w-100">
            <tr>
                <th style="width: 120px !important;">Date</th>
                <td>: {{ $requisition_expenses->date_format }}</td>
            </tr>
            <tr>
                <th style="width: 120px !important;">Code</th>
                <td>: {{ $requisition_expenses->code }}</td>
            </tr>
            <tr>
                <th style="width: 120px !important;">Employee</th>
                <td>: {{ $requisition_expenses->employee->employee_name_id }}</td>
            </tr>
            <tr>
                <th style="width: 120px !important;">Created By</th>
                <td>:
                    {{ $requisition_expenses->createdBy->uc_full_name }}
                </td>
            </tr>
            @if($requisition_expenses->transaction_id)
            <tr>
                <th style="width: 120px !important;">Transaction Code</th>
                <td>
                    : {{ $requisition_expenses->transaction->transaction_code }}
                </td>
            </tr>
            @endif
        </table>
        <br/>
        <table  width="400px" class="table table-responsive table-bordered margin-top-30 float-right">
            <tbody>
            <tr>
                <th>#SL</th>
                <th>Expense Name</th>
                <th class="text-right">Amount</th>
            </tr>
            @php $total = 0; @endphp

            @foreach($requisition_expenses->expenseDetails as $key => $value)

                <tr>
                    <td>{{ $key+1 }}</td>
                    <td class="text-left">{{ $value->expenseType->display_name }}</td>
                    <td class="text-right">{{ create_money_format($value->amount) }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2" class="text-right">  Total: </th>
                <td class="text-right">{{ create_money_format($requisition_expenses->total_amount) }}</td>
            </tr>

            </tbody>

        </table>
    </div>
</div>
</body>
</html>

