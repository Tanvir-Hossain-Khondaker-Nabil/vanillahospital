<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/7/2019
 * Time: 2:42 PM
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
                        <th> ID</th>
                        <th> {{ $type=="customer" || $type=="Sale" ? trans('common.customer') : trans('common.supplier') }} {{__('common.name')}}</th>
                        <th class="text-center"> {{__("common.paid_amount")}}</th>
                        <th class="text-center">{{__('common.due_amount')}}</th>
                        <th class="text-center"> {{__('common.discount_amount')}}</th>
                        <th class="text-center"> {{__('common.total_amount')}}</th>
                        <th class="text-center"> {{__('common.date')}}</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($modal as $key=>$value)
                    <tr>
                        <td> {{ $key+1 }} </td>
                        @if($type=="customer" || $type=="Sale")
                            <td> {{ $value->customer ? $value->customer->name : '' }} </td>
                            <td class="text-right"> {{ create_money_format($value->paid_amount) }} </td>
                            <td class="text-right"> {{ create_money_format($value->due) }} </td>
                            <td class="text-right"> {{ create_money_format($value->total_discount) }} </td>
                            <td class="text-right"> {{ create_money_format($value->total_price) }} </td>
                        @else
                            <td> {{ $value->supplier ? $value->supplier->name : '' }} </td>
                            <td class="text-right"> {{ create_money_format($value->paid_amount) }} </td>
                            <td class="text-right"> {{ create_money_format($value->due_amount) }} </td>
                            <td class="text-right"> {{ create_money_format($value->total_discount) }} </td>
                            <td  class="text-right"> {{ create_money_format($value->total_amount) }} </td>
                        @endif
                        <td> {{ $value->date_format }} </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
    </div>
</div>
</body>
</html>
