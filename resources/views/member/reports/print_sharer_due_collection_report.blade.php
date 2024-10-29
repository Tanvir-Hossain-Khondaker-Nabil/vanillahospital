<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/9/2019
 * Time: 11:36 AM
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
                    <th> {{__('common.collection_amount')}}</th>
                    <th> {{__('common.date')}}</th>
                </tr>
            </thead>
            <tbody>
            @foreach($modal as $key=>$value)
                <tr>
                    <td> {{ $key+1 }} </td>
                    <td> {{ $value->sharer_name == "" ? trans('common.unknown') : $value->sharer_name }} </td>
                    <td> {{ create_money_format($value->amount) }} </td>
                    <td> {{ db_date_month_year_format($value->date) }} </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>

