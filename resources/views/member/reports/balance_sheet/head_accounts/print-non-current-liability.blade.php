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

                @foreach($non_current_liabilities as $key=>$value)
                    <tr>
                        <td colspan="2">{{ $value['account_type_name'] }}</td>
                        <td class="text-right">{{ ($value['balance'] < 0 ? "(" : '').create_money_format( $value['balance'] ).($value['balance'] < 0 ? ")":'') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="2">Total Non-Current Liabilities</th>
                    <th class="dual-line text-right">{{ ($total_non_current_liability < 0 ? "(" : '').create_money_format( $total_non_current_liability).($total_non_current_liability < 0 ? ")" : '') }}</th>
                </tr>
            </table>

        </div>
    </div>
</div>
</body>
</html>



