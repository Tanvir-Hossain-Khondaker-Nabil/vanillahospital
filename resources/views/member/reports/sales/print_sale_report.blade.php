<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/23/2019
 * Time: 4:36 PM
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
                        <th>#SL</th>
                        <th  width="100px">Date</th>
                        <th class="text-left">>Sale Code</th>
                        <th class="text-left">>Customer Name</th>
                        <th>Discount</th>
                        <th class="text-right">Total Discount</th>
                         <th class="text-right">Due Amount</th>
                        <th class="text-right">Paid Amount</th>
                        <th class="text-right">Total Price</th>
                    </tr>
                </thead>
                <tbody>

                @php
                    $total_discount = 0;
                    $total_price = 0;
                    $total_paid = 0;
                    $total_due = 0;
                @endphp
                @foreach($sales as $key => $value)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ db_date_month_year_format($value->date) }}</td>
                        <td class="text-left">>{{ $value->sale_code }}</td>
                        <td class="text-left">>{{ $value->customer ? $value->customer->name : "" }}</td>
                        <td class="text-right">{{ $value->discount_type == "fixed" ? create_money_format($value->discount) : $value->discount."%" }}</td>
                        <td class="text-right">{{ create_money_format($value->total_discount) }}</td>
                        <td class="text-right">{{ create_money_format($value->due) }}</td>
                        <td class="text-right">{{ create_money_format($value->paid_amount) }}</td>
                        <td class="text-right">{{ create_money_format($value->total_price) }}</td>
                    </tr>
                    @php
                        $total_discount += $value->total_discount;
                        $total_price += $value->total_price;
                        $total_paid += $value->paid_amount;
                        $total_due += $value->due;
                    @endphp

                @endforeach

                <tr>
                    <th colspan="5"> Total </th>
                    <th class="text-right"> {{ create_money_format($total_discount) }}  </th>
                    <th  class="text-right">  {{ create_money_format($total_due) }}</th>
                    <th class="text-right">  {{ create_money_format($total_paid) }}</th>
                    <th class="text-right"> {{ create_money_format($total_price) }}</th>
                </tr>
                </tbody>
            </table>
    </div>
</div>
</body>
</html>



