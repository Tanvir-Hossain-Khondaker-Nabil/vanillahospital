<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/30/2019
 * Time: 3:03 PM
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
                <th>{{__('common.product_code')}}</th>
                <th>{{__('common.product_name')}}</th>
                <th> {{__('common.stock')}}</th>
            </tr>
            </thead>
            <tbody>
            @forelse($stocks as $key=>$value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->productCode }}</td>
                    <td>{{ $value->item_name }}</td>
                    <td>{{ ( $value->stock_details ? $value->stock_details->stock : 0)." ".$value->unit}}</td>
                </tr>
            @empty
            <tr>
                <td class="text-left" colspan="4">{{__('common.no_report_available_for_stock')}}</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>




