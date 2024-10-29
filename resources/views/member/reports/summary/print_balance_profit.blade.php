<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 3/20/2021
 * Time: 5:09 PM
 */

?>
@include('member.reports.print_head')
<style>
    th{
        text-align: left;
        width: 50%;
    }
</style>
<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="">

        <div  style="width: 100%;" >
            <h4> {{__('common.purchase_history')}} </h4>
            <table class="table table-striped" id="dataTable">

                <tbody>
                <tr>
                    <th> {{__('common.purchase')}} </th>
                    <td class="text-right">{{  create_money_format($total_purchase) }}</td>
                </tr>
                <tr>
                    <th> {{__("common.due_purchase")}} </th>
                    <td class="text-right">(+) {{  create_money_format($purchase_due) }}</td>
                </tr>
                <tr>
                    <th>  {{__('common.due_purchase')}}</th>
                    <td class="text-right"> {{  create_money_format($purchase_discount) }}</td>
                </tr>
                <tr>
                    <th> {{__('common.purchase_return')}} </th>
                    <td class="text-right">(-) {{ create_money_format($total_purchase_return) }}</td>
                </tr>
                @php
                    $purchase_amount = $total_purchase+$purchase_due-$total_purchase_return;
                @endphp
                <tr>
                    <th >  {{__('common.total_purchase_amount')}}</th>
                    <td class="text-right double-line">{{  create_money_format($purchase_amount) }}</td>
                </tr>
                </tbody>
            </table>
        </div>
        <div  style="width: 100%; margin-top: 20px;" >
            <h4> {{__('common.sale_history')}} </h4>
            <table class="table table-striped" id="dataTable">

                <tbody>
                <tr>
                    <th> {{__('common.sale')}} </th>
                    <td class="text-right">{{ create_money_format($total_sale) }}</td>
                </tr>
                <tr>
                    <th> {{__('common.sale_due')}} </th>
                    <td class="text-right">(+) {{ create_money_format($sale_due) }}</td>
                </tr>
                <tr>
                    <th> {{__('common.sale_discount')}} </th>
                    <td class="text-right">{{ create_money_format($sale_discount) }}</td>
                </tr>
                <tr>
                    <th> {{__('common.sale_return')}} </th>
                    <td class="text-right">(-) {{  create_money_format($total_sale_return) }}</td>
                </tr>
                @php
                    $sale_amount = $total_sale+$sale_due-$total_sale_return;
                     $total =    $sale_amount-$purchase_amount;
                @endphp
                <tr>
                    <th> {{__('common.total_sale')}}  </th>
                    <td class="text-right">{{  create_money_format($sale_amount) }}</td>
                </tr>

                <tr>
                    <th> <?=$total<0 ? trans('common.loss') : trans('common.profit')?>  </th>
                    <th class="text-right dual-line  <?=$total<0 ? 'font-italic text-red' : ''?>">{{  ($total<0 ? "(":"").create_money_format($total).($total<0 ? ")":"") }}</th>
                </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>

