<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/7/2019
 * Time: 4:41 PM
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
                    <th> 
                        {{-- {{ ucfirst($type) }} --}}
                        {{__('common.'.$type)}} {{__('common.name')}}</th>
                    <th class="text-center"> {{__('common.initial_amount')}}</th>
                    <th class="text-center"> {{__('common.due_amount')}}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($modal as $key=>$value)
                <tr>
                    <td> {{ $key+1 }} </td>
                    <td> {{ ucfirst($value->name) }} </td>
                    @if($type=="customer")
                        <td class="text-right"> {{ create_money_format($value->customer_initial_balance) }} </td>
                        <td class="text-right"> {{ create_money_format(-1*$value->customer_current_balance) }} </td>
                    @else
                        <td class="text-right"> {{ create_money_format($value->supplier_initial_balance) }} </td>
                        <td  class="text-right"> {{ create_money_format($value->supplier_current_balance) }} </td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

