<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 9/29/2021
 * Time: 3:51 PM
 */

?>
@if($value['balance'] != 0)
<tr>
    <td colspan="2"
        class=" {{ isset($value['child']) && $value['child'] == 'yes' ? 'padding-left-70 ' : 'padding-left-40'  }} {{ isset($value['parent']) && $value['parent'] == 'yes' ? ' text-bold ' : '' }}">{{ $value['account_type_name'] }}</td>
    <td class="text-right">{{ create_money_format($value['balance']) }}</td>
</tr>
@endif
