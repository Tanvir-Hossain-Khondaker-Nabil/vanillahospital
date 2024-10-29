<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/6/2021
 * Time: 12:58 PM
 */

?>

@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="width: 100%; display: flex; flex-wrap: nowrap; overflow: hidden; clear: both; margin-top: 50px;">

        <table class="table table-striped" id="dataTable">

            <tbody>
            <tr>
                <th class="text-center">#SL</th>
                <th class="text-center">{{__('common.date')}}</th>
                <th>{{__('common.product_name')}}</th>
                <th class="text-center" style="padding-right: 30px;">{{__('common.qty')}}</th>
                <th class="text-right">{{__('common.total_price')}}</th>
            </tr>
            @php
                $total_price  = $total_qty = 0;
            @endphp
            @foreach($purchase_details as $key => $value)
                <tr>
                    <td class="text-center"> {{ $key+1 }}</td>
                    <td class="text-center">{{ db_date_month_year_format($value->date)  }}</td>
                    <td>{{ $value->item->item_name }}</td>
                    <td class="text-right" style="padding-right: 30px;">{{ $value->total_qty." ".$value->unit }} </td>
                    <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>
                </tr>
                @php
                    $total_price += $value->sum_total_price;
                                    $total_qty += $value->total_qty;
                @endphp

                @if( $loop->last)
                    <tr>
                        <th colspan="3" class="text-right"> {{__('common.total')}} </th>

                        @if($item || !App\Models\Item::multiUnitCheck())
                            <th class="text-right"> {{ $total_qty." ".$value->unit }}</th>
                        @else
                            <th class="text-right"> {{ $total_qty }}</th>
                        @endif

                        <th  class="text-right"> {{ create_money_format($total_price) }}</th>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>

</div>
</div>
</body>
</html>

