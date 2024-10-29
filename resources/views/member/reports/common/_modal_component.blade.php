<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 6/15/2021
 * Time: 6:45 PM
 */
?>


    <tr>
        <th rowspan="2" class="text-left">Particulars</th>
        <th colspan="2" class="text-center">Opening Balance</th>
        <th colspan="2" class="text-center">Transaction</th>
        <th rowspan="2" class="text-center">Closing Balance</th>
    </tr>
    <tr>
        <th class="text-center"> Dr</th>
        <th class="text-center">Cr</th>
        <th class="text-center"> Dr</th>
        <th class="text-center">Cr</th>
    </tr>

    @foreach($accounts as $key=>$value)
        <tr>
            <td>{{ $value['account_type_name'] }}</td>
            @if($value['pre_balance']>0)
            <td class="text-right {{ $value['pre_balance'] < 0 ? " font-italic" :'' }}">{{ !isset($income_expense) ? create_money_format_with_dr_cr( $value['pre_balance'] ) : '' }}</td>
                <td></td>
            @else
                <td></td>
                <td class="text-right {{ $value['pre_balance'] < 0 ? " font-italic" :'' }}">{{ !isset($income_expense) ? create_money_format_with_dr_cr( $value['pre_balance'] ) : '' }}</td>
            @endif
            <td class="text-right ">{{ $value['total_dr']>0 ? create_money_format( $value['total_dr'] ) : "" }}</td>
            <td class="text-right ">{{ $value['total_cr']>0 ? create_money_format( $value['total_cr'] ) : "" }}</td>
            <td class="text-right {{ $value['balance'] < 0 ? " font-italic" :'' }}">{{ create_money_format_with_dr_cr( $value['balance'] ) }}</td>

        </tr>
@endforeach


