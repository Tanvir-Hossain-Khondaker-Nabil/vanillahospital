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

    <div style="width: 100%; display: flex; flex-wrap: nowrap; overflow: hidden; clear: both;">

        <table class="table table-striped" id="dataTable" >

            <tbody>
            <tr>
                <th class="text-left">Total Sale</th>
                <th colspan="5" class="text-right">{{ create_money_format($total_sale) }}</th>
            </tr>
            <tr>
                <th class="text-left">Cash Sale</th>
                <th  colspan="5" class="text-right">{{ create_money_format($total_paid) }}</th>
            </tr>
            <tr>
                <th class="text-left">Due Sale</th>
                <th colspan="5"  class="text-right">{{ create_money_format($total_due) }}</th>
            </tr>
            <tr>
                <th class="text-left" style="padding-left:80px; border-top: 1px solid #E0E2E4;">
                    Customer Name
                </th>
                <th class="text-left" style="border-top: 1px solid #E0E2E4;">Product Name</th>
                <th class="text-right" style="border-top: 1px solid #E0E2E4;">Total Qty</th>
                <th class="text-right" style="border-top: 1px solid #E0E2E4;">Unit</th>
                <th class="text-right" style="border-top: 1px solid #E0E2E4;">Total Price</th>
{{--                <th class="text-right" style="border-top: 1px solid #E0E2E4;">Due</th>--}}
            </tr>
            @php
                $customer_id =  null;
                $sale_id =  null;
                $total = $due_total =  0;
                $rowspan =  0;
            @endphp
            @foreach($sales as $value)

                @php
                    $customer_name =  $value->customer ? $value->customer->name_phone : '';
                    $due = ($value->total_due/$value->count);
                @endphp
                <tr>
                    <td class="text-left" style="padding-left:80px; border-top: 1px solid #E0E2E4;">
                        @if($customer_id != $value->customer_id)
                            {{ $customer_name }}
                        @endif
                    </td>
                    <td class="text-left" style="border-top: 1px solid #E0E2E4;">{{ $value->item_name }}</td>
                    <td class="text-right" style="border-top: 1px solid #E0E2E4;">{{ $value->total_qty }} </td>
                    <td class="text-right" style="border-top: 1px solid #E0E2E4;">{{ $value->unit }}</td>
                    <td class="text-right" style="border-top: 1px solid #E0E2E4;">{{ create_money_format($value->total_price) }}</td>
{{--                    <td class="text-right" style="border-top: 1px solid #E0E2E4;">--}}
{{--                        {{ create_money_format($due) }}--}}
{{--                    </td>--}}
                </tr>
                @php
                    $customer_id =  $value->customer_id;
                    $sale_id =  $value->id;
                    $total +=  $value->total_price;
                    $due_total = $due_total+$due;
                @endphp
            @endforeach

            <tr>
                <th colspan="4"  >Total</th>
                <th class="text-right">{{ create_money_format($total) }}</th>
{{--                <th class="text-right">{{ create_money_format($due_total) }}</th>--}}
            </tr>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>

