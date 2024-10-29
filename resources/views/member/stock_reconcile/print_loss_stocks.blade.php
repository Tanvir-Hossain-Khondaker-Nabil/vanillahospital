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

    <div>
        <h2 style="margin: 10px auto!important">{{__('common.stock_loss_report')}}</h2>
    </div>
    <div>
        <table cellspacing="0" width="100%" class="table">
            <thead>
            <tr>
                <th class="text-center">ID</th>
                <th class="text-center">{{__('common.product_code')}}</th>
                <th class="text-center">{{__('common.product_name')}}</th>
                <th class="text-center">{{__("common.stock")}}</th>
                <th class="text-center">{{__("common.date")}}</th>
                <th class="text-center">{{__('common.loss_type')}} </th>
                {{--<th class="text-center">Fiscal Year</th>--}}
            </tr>
            </thead>
            <tbody>
            @forelse($stocks as $key=>$value)
                <tr>
                    <td class="text-center">{{ $key+1 }}</td>
                    <td class="text-center">{{ $value->item->productCode }}</td>
                    <td class="text-center">{{ $value->item->item_name }}</td>
                    <td class="text-center">{{ $value->qty }} {{ $value->item->unit }}</td>
                    <td class="text-center">{{ db_date_month_year_format($value->closing_date) }}</td>
                    <td class="text-center">{{ ucfirst($value->loss_type) }}</td>
                    {{--<td class="text-center" width="300px">{{ $value->fiscal_year->fiscal_year_details }}</td>--}}
                </tr>
            @empty
            <tr>
                <td class="text-center" colspan="6">{{__('common.no_report_available_for_stock')}}</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>


</div>
</body>
</html>




