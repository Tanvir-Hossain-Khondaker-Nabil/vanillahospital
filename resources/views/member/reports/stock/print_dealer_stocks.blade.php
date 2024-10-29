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
                @if(Auth::user()->can(['super-admin','admin']) || Auth::user()->hasRole(['sales_man']))
                <th>Dealer Name</th>
                @endif
                <th>Product Code</th>
                <th>Product Name</th>
                <th> Stock</th>
            </tr>
            </thead>
            <tbody>
            @forelse($stocks as $key=>$value)
                <tr>
                    <td>{{ $key+1 }}</td>
                    @if(Auth::user()->can(['super-admin','admin']) || Auth::user()->hasRole(['sales_man']))
                    <td>{{ $value->dealer->user_phone }}</td>
                    @endif
                    <td>{{ $value->item->productCode }}</td>
                    <td>{{ $value->item->item_name }}</td>
                    <td>{{ $value->stock." ".$value->item->unit  }}</td>

                </tr>
            @empty
            <tr>
                <td class="text-left" colspan="4">No Report available for Stock</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>




