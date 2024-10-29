<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/11/2019
 * Time: 2:01 PM
 */


$return = 0;
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
                @foreach($purchase_return_details as $key=>$value)
                    <tr>
                        <td >{{ $value->item->item_name }}</td>
                        <td  class="text-center">{{ $value->total_qty." ".$value->unit." X ".$value->sum_total_price }}</td>
                        @php
                            $returnTotal =$value->total_qty*$value->sum_total_price;
                            $return +=$returnTotal;
                        @endphp
                        <td class="text-right">{{ create_money_format($returnTotal) }}</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="2">Total Purchases Return</th>
                    <th class="dual-line text-right">{{ create_money_format( $return) }}</th>
                </tr>
            </table>

        </div>
    </div>
</div>
</body>
</html>



