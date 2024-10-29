<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/22/2019
 * Time: 4:59 PM
 */
?>

@include('member.reports.print_head')
<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="width: 100%; display: flex; flex-wrap: nowrap;">
        <table class="table table-striped" id="dataTable">
            <thead>
                <tr>
                    <th>#SL</th>
                    <th width="100px;">{{__('common.return_date')}}</th>
                    <th width="100px;">{{__('common.purchase_date')}}</th>
                    <th class="text-left">{{__('common.supplier_name')}}</th>
                    <th class="text-left">{{__('common.product_name')}}</th>
                    <th>{{__('common.unit')}}</th>
                    <th class="text-center">{{__('common.quantity')}}</th>
                    <th class="text-right">{{__('common.price')}}</th>
                    <th class="text-right">{{__('common.total_price')}}</th>
                </tr>
            </thead>
            <tbody>
            @php
                $last_date = 0;
                $purchase_total_price = $total_qty = 0;
                $product_name = $last_unit = '';
            @endphp
            @foreach($purchases as $key => $value)
                @if( !$loop->first && ($last_date!=0 ||  $last_date != db_date_month_year_format($value->purchases->date)) &&  $product_name!=$value->item->item_name)
                    <tr class=" margin-bottom-20">
                        <th colspan="6" class="text-right">{{__('common.total')}}</th>
                        <th colspan="2" class="text-right">{{ $total_qty." ".$last_unit }}</th>
                        <th colspan="2" class="text-right">{{ create_money_format($purchase_total_price) }}</th>
                    </tr>
                    @php
                        $purchase_total_price = $total_qty = 0;
                    @endphp
                @endif
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td >{{ db_date_month_year_format($value->return_date) }}</td>
                    <td >{{ db_date_month_year_format($value->purchases->date) }}</td>
                    <td class="text-left">{{ $value->purchases->supplier->name }}</td>
                    <td class="text-left">{{ $value->item->item_name }}</td>
                    <td>{{ $value->unit }}</td>
                    <td class="text-center">{{ $value->return_qty }}</td>
                    <td class="text-right">{{ create_money_format($value->return_price) }}</td>
                    <td class="text-right">{{ create_money_format($value->return_qty*$value->return_price) }}</td>
                </tr>
                @php
                    $last_date = db_date_month_year_format($value->purchases->date);
                    $purchase_total_price += $value->return_qty*$value->return_price;
                    $product_name = $value->item->item_name;
                    $total_qty += $value->return_qty;
                    $last_unit = $value->unit;
                @endphp

                @if( $loop->last)
                    <tr class=" margin-bottom-20">
                        <th colspan="6" class="text-right">{{__('common.total')}}</th>
                        <th colspan="2" class="text-right">{{ $total_qty." ".$last_unit }}</th>
                        <th colspan="2" class="text-right">{{ create_money_format($purchase_total_price) }}</th>
                    </tr>
                    @php
                        $purchase_total_price = 0;
                    @endphp
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

