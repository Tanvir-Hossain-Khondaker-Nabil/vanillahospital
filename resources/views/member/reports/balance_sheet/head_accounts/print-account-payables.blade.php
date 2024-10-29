<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */

?>

@include('member.reports.common.head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="width: 100%;">

        <div class="col-md-12">
            <table class="" style="width: 100%;">
                <tr>
                    <th colspan="2">Particulars</th>
                    <th class="text-right">Taka</th>
                </tr>

                @foreach($account_payables as $key=>$value)
                    <tr>
                        <td colspan="2">{{ $value['account_type_name'] }}</td>
                        <td class="text-right">{{ create_money_format( $value['balance'] ) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="2">Total Account Payable</th>
                    <th class="dual-line text-right">{{ create_money_format( $total_account_payables) }}</th>
                </tr>
            </table>

        </div>
    </div>
</div>
</body>
</html>



