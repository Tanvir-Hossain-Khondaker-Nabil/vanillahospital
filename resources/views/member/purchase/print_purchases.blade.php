<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/29/2020
 * Time: 2:30 PM
 */
?>

@include('member.reports.print_head')

<body>
<div id="page-wrap">

    @include('member.reports.company')

    <div style=" text-align: center!important;">
        <div  style="width: 100%; ">

            <div style="width: 100%; ">
                <div  style="width: 100%; margin: 10px 0; ">
                    @php print_r($purchase_barcode) @endphp<br>
                </div>

            </div>
            <!-- title row -->

            <!-- info row -->
            <div  class="row invoice-info">

                <div  style="width: 50%; padding: 10px 10px 10px 0;  float: left; ">
                    <table class="text-left">
                        <tr >
                            <th >Date: </th>
                            <td >{{ $purchase->date_format }}</td>
                        </tr>
                        <tr >
                            <th >Invoice No:</th>
                            <td >{{ $purchase->memo_no }}</td>
                        </tr>
                        <tr >
                            <th >Memo No:</th>
                            <td >{{ $purchase->invoice_no }}</td>
                        </tr>
                        @if( $purchase->chalan_no )
                            <tr >
                                <th >Chalan No:</th>
                                <td >{{ $purchase->chalan_no }}</td>
                            </tr>
                        @endif
                        <tr >
                            <th >Account:</th>
                            <td >{{ $purchase->cash_or_bank->title }}</td>
                        </tr>
                        <tr >
                            <th >Payment Method:</th>
                            <td >{{ $purchase->payment_method->name }}</td>
                        </tr>
                        {{--                                @if( $purchase->vehicle_number )--}}
                        <tr >
                            <th >Vehicle Number:</th>
                            <td >{{ $purchase->vehicle_number }}</td>
                        </tr>
                        {{--                                @endif--}}
                    </table>
                </div>
                @if($purchase->supplier)
                    <div style="width: 48%;  float: left; padding: 10px 0 10px; ">
                        <h3 class="text-right">Supplier Info</h3>
                        <table class="text-right customer-table-info">
                            <tr>
                                <td> {{ $purchase->supplier->name }}</td>
                            </tr>
                            <tr >
                                <td>{{ $purchase->supplier->address }}</td>
                            </tr>
                            <tr>
                                <td>{{ $purchase->supplier->phone }}</td>
                            </tr>
                        </table>
                    </div>
                @endif
            </div>
            <!-- /.row -->


            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <!-- BEGIN SAMPLE FORM PORTLET-->

                <div class="table-responsive">

                    <table class="table" >
                        <tbody>
                        <tr>
                            <th>Item Name</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th class="text-center">Total Price</th>
                        </tr>
                        @php $total = 0; @endphp
                        @foreach($purchase->purchase_details as $value)
                            <tr>
                                <td>{{ $value->item->item_name }}</td>
                                <td>{{ $value->description }}</td>
                                <td>{{ $value->unit }}</td>
                                <td> {{ $value->qty }}</td>
                                <td> {{ create_money_format($value->price) }}</td>
                                <td class="text-right" >{{ create_money_format($value->total_price) }}</td>
                            </tr>
                            @php
                                $total += $value->total_price;
                            @endphp
                        @endforeach

                        <tr>
                            <th> Notes: </th>
                            <td colspan="5" >
                                @php print_r($purchase->notation) @endphp
                            </td>
                        </tr>
                        </tbody>

                    </table>

                    <table class=" pull-right" style="width: 400px; margin-top: 30px; ">

                        <tr>
                            <th class="text-right" colspan="5"> Sub Total</th>
                            <th class="text-right" > {{ create_money_format($total) }} </th>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="5">Transport Cost</td>
                            <td class="text-right" > {{ create_money_format($purchase->transport_cost) }} </td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="5">Unload Cost</td>
                            <td class="text-right" > {{ create_money_format($purchase->unload_cost) }} </td>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="5">Grand Total</th>
                            <th class="text-right" > {{ create_money_format($purchase->total_amount) }} </th>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="5">Discount ({{ $purchase->discount_type == "Percentage" ? $purchase->discount."%" : $purchase->discount_type }})</td>
                            <td class="text-right" > {{ $purchase->total_discount > 0 ? "- (".create_money_format($purchase->total_discount).")" : create_money_format(0.00) }} </td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="5">Amount To Pay</td>
                            <td class="text-right" > {{ create_money_format($purchase->amt_to_pay) }} </td>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="5">Paid Amount</th>
                            <th class="text-right" > {{ create_money_format($purchase->paid_amount) }} </th>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="5">Due Amount</td>
                            <td class="text-right" > {{ create_money_format($purchase->due_amount) }} </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>

