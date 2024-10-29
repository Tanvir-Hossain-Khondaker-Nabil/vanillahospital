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
                    @php print_r($barcode) @endphp<br>
                </div>

            </div>
            <!-- title row -->

            <!-- info row -->
            <div  class="row invoice-info">

                <div  style="width: 50%; padding: 10px 10px 10px 0;  float: left; ">
                    <table class="text-left">
                        <tr >
                            <th >Date: </th>
                            <td >{{ $requisition->date_format }}</td>
                        </tr>
                        <tr >
                            <th >Invoice No:</th>
                            <td >{{ $requisition->id }}</td>
                        </tr>

                        <tr >
                            <th >Customer Name</th>
                            <td >
                                @if($requisition->creator->sharer)
                                    {{ $requisition->creator->sharer->name }} <br/>
                                    {{ $requisition->creator->sharer->phone }}<br/>
                                    {{ $requisition->creator->sharer->address }}
                                @else
                                    {{ $requisition->creator->full_name }} <br/>
                                    {{ $requisition->creator->phone }}<br/>
                                    {{ $requisition->creator->email }}
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
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
                            {{--<th>Carton</th>--}}
                            {{--<th>Free</th>--}}
                            {{--<th>Total Qty</th>--}}
                            <th>Price</th>
                            <th class="text-center">Total Price</th>
                        </tr>
                        @php $total = 0; @endphp

                        @foreach($requisition->sales_requisition_details as $value)
                            <tr>
                                <td>{{ $value->item->item_name }}</td>
                                <td>{{ $value->description }}</td>
                                <td>{{ $value->unit }}</td>
                                <td> {{ $value->qty }}</td>

                                {{--<td> {{ $value->carton }}</td>--}}
                                {{--<td> {{ $value->free }}</td>--}}
                                {{--<td> {{ $value->qty+$value->free }}</td>--}}
                                <td> {{ create_money_format($value->price) }}</td>
                                <td class="text-right" >{{ create_money_format($value->total_price) }}</td>
                            </tr>
                            @php
                                $total += $value->total_price;
                            @endphp
                        @endforeach

                        <tr style="border: 0px !important;">
                            <th style="border: 0px !important;"> Notes: </th>
                            <td  style="border: 0px !important;" colspan="5" >
                                @php print_r($requisition->notation) @endphp
                            </td>
                        </tr>
                        </tbody>

                    </table>

                    <table class=" pull-right" style="width: 400px; margin-top: 30px; ">

                        <tr>
                            <th class="text-right" colspan="5"> Sub Total</th>
                            <th class="text-right" > {{ create_money_format($total) }} </th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
</body>
</html>

