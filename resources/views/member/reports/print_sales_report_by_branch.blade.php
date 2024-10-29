<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 9/19/2019
 * Time: 3:34 PM
 */
?>

@include('member.reports.print_head')

<body>
<div id="page-wrap">

@include('member.reports.company')

<div>
    <table width="100%" class="table">
        <thead>
            <tr>
                <th>#SL</th>
                <th>Branch Name</th>
                <th class="text-right">Total Discount</th>
                <th class="text-right">Total Due</th>
                <th class="text-right">Total Shipping Charge</th>
                <th class="text-right">Total Paid </th>
                <th class="text-right">Total Price</th>
            </tr>
        </thead>
        <tbody>

        @php
            $total_discount = 0;
            $total_price = 0;
            $total_paid = 0;
            $total_due = 0;
            $total_charge = 0;
        @endphp
        @foreach($sales_report as $key => $value)
            <tr>
                <td>{{ $key+1 }}</td>
                <td>{{ $value->branch ? $value->branch->display_name : "Head" }}</td>
                <td class="text-right">{{ create_money_format($value->total_discount) }}</td>
                <td class="text-right">{{ create_money_format($value->total_due) }}</td>
                <td class="text-right">{{ create_money_format($value->total_shipping_charge) }}</td>
                <td class="text-right">{{ create_money_format($value->total_paid) }}</td>
                <td class="text-right">{{ create_money_format($value->total_price) }}</td>
            </tr>
            @php
                $total_discount += $value->total_discount;
                $total_price += $value->total_price;
                $total_paid += $value->total_paid;
                $total_due += $value->total_due;
                $total_charge += $value->total_shipping_charge;
            @endphp

        @endforeach

        <tr>
            <th colspan="2"> Total </th>
            <th class="text-right"> {{ create_money_format($total_discount) }}  </th>
            <th  class="text-right">  {{ create_money_format($total_due) }}</th>
            <th  class="text-right">  {{ create_money_format($total_charge) }}</th>
            <th class="text-right">  {{ create_money_format($total_paid) }}</th>
            <th class="text-right"> {{ create_money_format($total_price) }}</th>
        </tr>
        </tbody>
    </table>
</div>
</div>
</body>
</html>


