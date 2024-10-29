<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 18-Feb-20
 * Time: 12:53 PM
 */

?>

@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="width: 100%; display: flex; flex-wrap: nowrap;">
        <table class="table">
            <thead>
            <tr>
                <th colspan="3">
                    <h3 style="margin: 10px 0;"> {{ isset($account_types) ? "Account Of ". $account_types->display_name : ""}} </h3>
                </th>
            </tr>
            <tr>
                <th class="text-center">Date</th>
                <th>Balance</th>
            </tr>
            </thead>
            <tbody>

            @foreach($modal as $key => $value)
                <tr>
                    <td class="text-center">{{ db_date_month_year_format($value->date) }}</td>
                    <td class="text-right ">{{ create_money_format($value->balance) }}</td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
</div>
</body>
</html>



