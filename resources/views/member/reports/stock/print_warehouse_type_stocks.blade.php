<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/30/2019
 * Time: 3:03 PM
 */
?>


@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style="overflow: hidden; clear: both;">
        <table cellspacing="0" width="100%" class="table">
            <thead>
            <tr>
                <th>#SL</th>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Warehouse </th>
                <th>{{ucfirst($stock_type)}} Stock</th>
                <th>Type</th>
            </tr>
            </thead>
            <tbody>
            @forelse($stocks as $key=>$value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->item->productCode }}</td>
                    <td>{{ $value->item->item_name }}</td>
                    <td>{{ $value->warehouse->title }}</td>
                    <td>{{ $value->qty." ".$value->item->unit }}</td>
                    <td>{{ $value->model }}</td>

                </tr>
            @empty
            <tr>
                <td class="text-left" colspan="5">No Report available for Stock</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>




