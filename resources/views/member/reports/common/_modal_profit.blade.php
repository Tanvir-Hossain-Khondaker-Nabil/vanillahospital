<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 10/27/2020
 * Time: 5:00 PM
 */
?>


<div class="modal fade" id="inventories" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog profit-loss-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($inventory_no) }} Inventories  <a
                        href="{{ route('member.report.head_inventory') }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">

                    <tr>
                        <th colspan="3">Inventory</th>
                    </tr>

                    <tr>
                        <th>Product name </th>
                        <th class="text-center "> Quantity X Price</th>
                        <th class="text-right"> Total Price</th>
                    </tr>
                    @foreach($inventories as $key=>$value)

                        @if($value->pre_qty != 0)
                            <tr>
                                <td>{{ $value->product_name }}</td>
                                <td class="text-center">{{ $value->pre_qty }}{{ $value->unit }} X {{ create_money_format($value->pre_item_price) }}</td>
                                <td class="text-right">{{ create_money_format($value->pre_qty*$value->pre_item_price) }}</td>
                            </tr>
                        @endif

                    @endforeach
                    <tr>
                        <th colspan="2">Total Opening Stock</th>
                        <th class="dual-line text-right"> {{ create_money_format($pre_total_inventory) }} </th>
                    </tr>

                    <tr>
                        <td colspan="3" class="border-top-1" height="30px"></td>
                    </tr>

                    @foreach($inventories as $key=>$value)
                        @if($value->qty != 0)
                            <tr>
                                <td>{{ $value->product_name }}</td>
                                <td class="text-center ">{{ $value->qty }}{{ $value->unit }} X {{ create_money_format($value->item_price) }}</td>
                                <td class="text-right">{{ create_money_format($value->qty*$value->item_price) }}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <th colspan="2">Total Closing Stock</th>
                        <th class="dual-line text-right"> {{ create_money_format($total_inventory) }} </th>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="sales" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog profit-loss-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($sale_no) }} Sales  <a
                        href="{{ route('member.report.head_sales_report').$parameter }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">

                    @foreach($sale_details as $key=>$value)
                        <tr>
                            <td colspan="1">{{ $value->item->item_name }}</td>
                            <td colspan="1" class="text-right">{{ $value->total_qty." ".$value->unit }}</td>
                            <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total Sales</th>
                        <th class="dual-line text-right">{{ create_money_format( $total_sales) }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="purchases" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog profit-loss-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($purchase_no) }} Purchases  <a
                        href="{{ route('member.report.head_purchases_report').$parameter }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">

                    @foreach($purchase_details as $key=>$value)
                        <tr>
                            <td colspan="1">{{ $value->item->item_name }}</td>
                            <td colspan="1" class="text-right">{{ $value->total_qty." ".$value->unit }}</td>
                            <td class="text-right">{{ create_money_format($value->sum_total_price) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total Purchases</th>
                        <th class="dual-line text-right">{{ create_money_format( $total_purchases_details) }}</th>
                    </tr>
                    <tr>
                        <th colspan="2">Total Purchases Cost(Bank Charge+unload+Transport)</th>
                        <th class="dual-line text-right">{{ create_money_format( $total_purchases_bank_charge) }}</th>
                    </tr>
                    <tr>
                        <th colspan="2">Total</th>
                        <th class="dual-line text-right">{{ create_money_format($total_purchases) }}</th>
                    </tr>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="purchases_return" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog profit-loss-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($purchase_return_no) }} Purchases Return <a
                        href="{{ route('member.report.head_purchases_return_report').$parameter }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">

                    @php
                        $return = 0;
                    @endphp
                    @foreach($purchase_return_details as $key=>$value)
                        @php
                            $returnTotal =$value->total_qty*$value->sum_total_price;
                            $return +=$returnTotal;
                        @endphp
                        <tr>
                            <td colspan="1">{{ $value->item->item_name }}</td>
                            <td colspan="1" class="text-right">{{ $value->total_qty." ".$value->unit." X ".$value->sum_total_price }}</td>
                            <td class="text-right">{{ create_money_format($returnTotal) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total Purchases Return</th>
                        <th class="dual-line text-right">{{ create_money_format( $total_purchases_return) }}</th>
                    </tr>

                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="sales_return" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog profit-loss-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="exampleModalLongTitle">{{ create_money_format($sale_return_no) }} Sales Return <a
                        href="{{ route('member.report.head_sales_return_report').$parameter }}" class="pull-right"
                        id="btn-print"><i class="fa fa-print"></i> Print </a> </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <table class="table">

                    @php
                        $return = 0;
                    @endphp
                    @foreach($sale_return_details as $key=>$value)
                        @php
                            $returnTotal =$value->total_qty*$value->sum_total_price;
                            $return +=$returnTotal;
                        @endphp
                        <tr>
                            <td colspan="1">{{ $value->item->item_name }}</td>
                            <td colspan="1" class="text-right">{{ $value->total_qty." ".$value->unit." X ".$value->sum_total_price }}</td>
                            <td class="text-right">{{ create_money_format($returnTotal) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <th colspan="2">Total Sales Return</th>
                        <th class="dual-line text-right">{{ create_money_format( $total_sales_return) }}</th>
                    </tr>

                </table>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
