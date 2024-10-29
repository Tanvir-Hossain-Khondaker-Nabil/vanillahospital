<!-- Modal -->
    <div class="modal-dialog" role="document" style="width: 800px;">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('common.sale_code') }}: {{ $sales->sale_code }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 10px; " class="row">

                    <div class="col-md-5 px-4">
                        <table class="bill-info w-100">
                            <tr >
                                <th >{{__('common.date')}}: </th>
                                <td >{{ $sales->date_format }}</td>
                            </tr>
                            <tr >
                                <th >{{__('common.invoice_no')}}:</th>
                                <td >{{ "INV-".$sales->id }}</td>
                            </tr>
                            <tr >
                                <th >{{__('common.memo_no')}}:</th>
                                <td >{{ $sales->memo_no }}</td>
                            </tr>
                            <tr >
                                <th >{{__('common.account')}}:</th>
                                <td >{{ $sales->cash_or_bank->title }}</td>
                            </tr>
                            <tr >
                                <th >{{__('common.payment_method')}}:</th>
                                <td >{{ $sales->payment_method->name }}</td>
                            </tr>
                        </table>
                    </div>
                    @if($sales->customer)
                        <div style="border--left: 1px solid #d2d1d1;" class="col-md-push-3 col-md-4 px-5 ">
                            <h4>{{__('common.customer_info')}}:</h4>
                            <table class="w-100 customer-table-info">
                                <tr >
                                    <th class="w-25">{{__('common.name')}}: </th>
                                    <td class="w-75"> {{ $sales->customer->name }}</td>
                                </tr>
                                <tr >
                                    <th class="w-25">{{__('common.address')}}:</th>
                                    <td class="w-75">{{ $sales->customer->address }}</td>
                                </tr>
                                <tr >
                                    <th class="w-25">{{__('common.phone')}}:</th>
                                    <td class="w-75">{{ $sales->customer->phone }}</td>
                                </tr>
                            </table>
                        </div>
                    @endif
                </div>
                <!-- Table row -->
                <div class="row">
                    <div class="col-md-12 ">
                        <table style="width: 100%" class="sales_table">
                            <thead>
                            <tr class=" bg-gray">
                                <th width="50px">SL</th>
                                {{--<th>Item Code</th>--}}
                                <th>{{__('common.item_name')}} </th>
                                <th>{{__('common.serial')}}</th>
                                <th>{{__('common.warranty_expire')}}</th>
                                <th>{{__('common.guarantee_expire')}}</th>
                                <th class="text-center">{{__('common.quantity')}}</th>
                                <th class="text-center">{{__('common.price_per_qty')}}</th>
                                <th class="text-center"> {{__('common.price')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $total = 0;
                            @endphp
                            @foreach( $sales->sale_details as $key=>$sale)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $sale->item->item_name }}</td>
                                    <td>{{ $sale->product_info }}</td>
                                    <td>{{ formatted_date_string($sale->warranty_end_date) }}</td>
                                    <td>{{ formatted_date_string($sale->gurrantee_end_date) }}</td>
                                    <td class="text-center">{{ $sale->qty }}</td>
                                    <td class="text-center">{{ create_money_format($sale->price) }}</td>
                                    <td class="text-center">{{create_money_format($sale->total_price) }}</td>
                                </tr>

                                @php
                                    $total += $sale->total_price;
                                @endphp
                            @endforeach

                            <tr>
                                <td colspan="3" rowspan="6"><b>{{__('common.notation')}}:</b> {{ $sales->notation }}</td>
                                <td colspan="3" rowspan="6" >
                                    <h1 class="text-uppercase text-center"> {{ $sales->due> 0 ? trans('common.unpaid') : trans('common.paid') }}</h1>
                                </td>
                                <td class="text-right" >{{__('common.sub_total')}}:</td>
                                <td class="text-right" >{{ create_money_format($total) }}</td>
                            </tr>
                            <tr>
                                <td class="text-right" >{{__('common.discount')}} {{ $sales->discount_type=="fixed" ? "(Fixed)" : "(".$sales->discount."%)" }} :</td>
                                <td class="text-right">(-) {{ create_money_format($sales->total_discount) }}</td>
                            </tr>
                            <tr>
                                <td class="text-right" >{{__('common.total_amount')}}:</td>
                                <td class="text-right">{{ create_money_format($sales->grand_total) }}</td>
                            </tr>
                            <tr>
                                <td class="text-right" > {{__('common.amount_to_pay')}}:</td>
                                <td class="text-right">{{ create_money_format($sales->amount_to_pay) }}</td>
                            </tr>
                            <tr>
                                <th class="text-right">{{__('common.paid_amount')}}:</th>
                                <th class="text-right">{{ create_money_format($sales->paid_amount) }} </th>
                            </tr>
                            <tr>
                                <td class="text-right" >{{__('common.due_amount')}}:</td>
                                <td class="text-right"> {{ create_money_format($sales->due) }}</td>
                            </tr>

                            <tr>
                                <td colspan="10" class="text-left">
                                    <span class="text-bold"> {{__('common.in_words')}}: </span> {{ AmountInWords($sales->amount_to_pay) }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
