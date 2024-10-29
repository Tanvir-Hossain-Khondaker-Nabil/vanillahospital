<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 8/8/2023
 * Time: 4:57 PM
 */

?>


@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="overflow: hidden; clear: both;">
        <table cellspacing="0" width="100%" class="table">
            <thead>
            <tr>
                <th>#SL</th>
                <th>Date</th>
                <th>Code</th>
                <th>Project</th>
                <th>Expense Name</th>
                <th>Amount</th>
            </tr>
            </thead>
            <tbody>
            @php
                $total= 0;
            @endphp
            @foreach($project_expenses as $key => $value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->project_expense->date_format }}</td>
                    <td>{{ $value->project_expense->code }}</td>
                    <td>{{ $value->project_expense->project->project }}</td>
                    <td>{{ $value->projectExpenseType->display_name }}</td>
                    <td class="text-right">{{ create_money_format($value->amount) }}</td>
                </tr>
                @php
                    $total +=$value->amount;
                @endphp
            @endforeach
            @if(count($project_expenses) == 0)
                <tr>
                    <td class="text-left" colspan="6">No Report available</td>
                </tr>
            @else
                <tr>
                    <td class="text-right" colspan="5">Total</td>
                    <td class="text-right" > {{ create_money_format($total) }}</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

