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
                <th>Document Date</th>
                <th>Event Date</th>
            </tr>

            <tr>
                <td>{{ $journal['transaction_code'] }}</td>
                <td>{{ $journal['date'] }}</td>
                <td>{{ $journal['document_date'] }}</td>
                <td>{{ $journal['event_date'] }}</td>
            </tr>
            <tr>
                <th>Transaction Method</th>
                <th > Entry By</th>
                <th colspan="2"> Source Resource</th>
            </tr>
            <tr>
                <td>{{ $journal['method'] }}</td>
                <td class="text-capitalize">{{ $journal['entry_by'] }}</td>
                <td class="text-capitalize" colspan="2">{{ $journal['source_reference'] }}</td>
            </tr>

            </tbody>
        </table>
        <table class="table table-responsive table-bordered margin-top-30" >
            <tbody>
            <tr>
                <th>Jounral Date</th>
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

            @foreach($journal['transaction'] as $key => $value)

                <tr>
                    <td>{{  db_date_month_year_format($value->date) }}</td>
                    <td>{{  format_number_digit($value->account_type->id)   }}</td>
                    <td>{{  $value->account_type->display_name }}</td>
                    <td  class="text-right">{{ $value->transaction_type == 'dr' ? create_money_format( $value->amount) : "" }}</td>
                    <td class="text-right">{{   $value->transaction_type == 'cr' ? create_money_format( $value->amount) : "" }}</td>
                    <td>{{ $value->transaction_details->description }}</td>
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
                <td colspan="5"> {{ $journal['notation'] }}</td>
            </tr>
            </tbody>
        </table>

    </div>
</div>
</body>
</html>

