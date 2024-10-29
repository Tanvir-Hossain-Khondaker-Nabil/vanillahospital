<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/25/2019
 * Time: 10:24 AM
 */

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('member.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'sales',
        'href' => route('member.sales.index'),
    ],
    [
        'name' => 'Show',
    ],
];

$data['data'] = [
    'name' => 'Invoice',
    'title'=> 'Invoice',
    'heading' => trans('common.invoice'),
];

?>
@extends('layouts.back-end.master', $data)


@section('contents')
    <div class="row text-right">
        <div class="col-md-12">


            @if(config('settings.warehouse') && session('status'))

            <div class="box">
                <div class="callout callout-info text-left">
                    <h4>  {{__('common.unload_from_warehouse')}}</h4>

                    <p> {{__('common.do_you_want_to_proceed_this_sale')}} </p>
                    <a class="btn btn-default text-black text-bold " onclick="" href="{{ route('member.warehouse.unload', $sales->id) }}"> <i class="fa fa-arrow-right"> </i> {{__('common.proceed_unload')}} </a>
                </div>
            </div>

            @endif


            <div class="box">
                @include('common._alert')

                <div class="box-body">

                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span> {{__('common.chalan')}}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a  id="btn-print" href="{{ route('member.sales.print_calan', $sales->id) }}"> <i class="fa fa-print"></i>   {{__('common.print')}}</a></li>
                            <li><a href="{{ route('member.sales.print_calan', $sales->id) }}?based=download&pad_type=with"  ><i class="fa fa-download"></i>  {{__('common.download')}} </a></li>
                            <li><a href="{{ route('member.sales.print_calan', $sales->id) }}?based=download&pad_type=without"  ><i class="fa fa-download"></i>  {{__('common.download_without_pad')}} </a></li>
                        </ul>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-info btn-sm dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span> {{__('common.invoice')}}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a  id="btn-print" href="{{ route('member.sales.print_sale', $sales->id) }}"> <i class="fa fa-print"></i>   {{__('common.print')}}</a></li>
                            <li><a href="{{ route('member.sales.print_sale', $sales->id) }}?based=download&pad_type=with"  ><i class="fa fa-download"></i>  {{__('common.download')}} </a></li>
                            <li><a href="{{ route('member.sales.print_sale', $sales->id) }}?based=download&pad_type=without"  ><i class="fa fa-download"></i>  {{__('common.download_without_pad')}} </a></li>
                        </ul>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn bg-olive btn-sm dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span> {{__('common.profit_invoice')}}
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('member.sales.show', $sales->id) }}?based=profit_invoice"  ><i class="fa fa-eye"></i>  {{__('common.show')}} </a></li>
                            <li><a  id="btn-print" href="{{ route('member.sales.print_sale', $sales->id) }}?based=profit_invoice"> <i class="fa fa-download"></i>   {{__('common.print')}}</a></li>

                        </ul>
                    </div>
                    @if(config('settings.warehouse'))

                        <a class="btn btn-xs bg-purple" href="{{ route('member.warehouse.unload', $sales->id) }}" ><i class="fa fa-arrow-right"></i> {{__('common.unload_warehouse')}}</a>

                    @endif
                    {{--<a href="{{ route('member.sales.print_calan', $sales->id) }}" class="btn btn-xs btn-info" id="btn-print"><i class="fa fa-print"></i> Calan Print </a>--}}
                    {{--<a href="{{ route('member.sales.print_calan', $sales->id) }}?based=download&pad_type=with" class="btn btn-xs btn-flickr" ><i class="fa fa-download"></i> Calan Download </a>--}}
                    {{--<a href="{{ route('member.sales.print_calan', $sales->id) }}?based=download&pad_type=without" class="btn btn-xs bg-purple" ><i class="fa fa-download"></i> Calan Download without Pad</a>--}}
                    {{--<a href="{{ route('member.sales.print_sale', $sales->id) }}" class="btn btn-xs btn-primary" id="btn-print"><i class="fa fa-print"></i> Print </a>--}}

                    {{--<a href="{{ route('member.sales.print_sale', $sales->id) }}?based=download&pad_type=without" class="btn btn-xs bg-olive"><i class="fa fa-download"></i> Download Invoice Without Pad </a>--}}
                    {{--<a href="{{ route('member.sales.print_sale', $sales->id) }}?based=download&pad_type=with" class="btn btn-xs btn-github"><i class="fa fa-download"></i> Download Invoice  </a>--}}
                    {{--<a href="{{ route('member.sales.print_sale', $sales->id) }}?based=profit_invoice" class="btn btn-xs btn-dropbox" id="btn-print"><i class="fa fa-print"></i> Print Profit Invoice </a>--}}
                    {{--<a href="{{ route('member.sales.show', $sales->id) }}?based=profit_invoice" class="btn btn-xs btn-dropbox"><i class="fa fa-print"></i> Show Profit Invoice </a>--}}

                    <a href="{{ route('member.sales.sales_return', $sales->id) }}" class="btn btn-xs btn-info">
                        <i class="fa fa-reply"></i> {{__('common.sale_return')}}
                    </a>

                    <a href="{{ route('member.sales.edit', $sales->id) }}" class="btn btn-xs btn-success">
                        <i class="fa fa-pencil"></i> {{__('common.Edit')}}
                    </a>
                    <a href="{{ route('member.sales.create') }}" class="btn btn-xs btn-danger"><i class="fa fa-plus"></i> {{__('sale.add_sale')}}</a>
                    <a href="{{ route('member.sales.whole_sale_create') }}" class="btn btn-xs btn-warning"><i class="fa fa-plus"></i> {{__('sale.whole_sale')}}</a>
                    <a href="javascript:void(0);"  class="btn btn-xs btn-danger delete-confirm" data-target="{{route('member.transaction.destroy', $sales->transaction->id)}}">
                        <i class="fa fa-trash"></i> {{__('common.Delete')}}</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="custom-print">
        <div class="col-md-12">
            <div class="box">

                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-4 text-left">
                            @if( isset($company_logo))
                                <img src="{{ $company_logo }}" width="60px;"/>
                            @endif
                        </div>
                        <div class="col-xs-4 text-center py-5">
                            @php print_r($sale_barcode) @endphp<br>
                            <b style="margin-left: 13px;">{{ $sales->sale_code }}</b><br>
                        </div>
                        <div class="col-xs-4 text-right company-info">
                            <h2 style="margin: 10px auto!important">{{ $company_name }}</h2>
                            <div class="info">
                                <p >{{ $company_address }} </p>
                                <p >{{ $company_city.($company_country ? ", ".$company_country : "") }}</p>
                                <p >{{__('common.phone')}} : {{ $company_phone }}</p>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- title row -->

                    <!-- info row -->
                    <div style="margin-bottom: 10px; " class="row invoice-info">

                        <div class="col-md-5 p-5">
                            <table class="bill-info w-100">
                                <tr >
                                    <th >{{__('common.date')}}: </th>
                                    <td >{{ $sales->date_format }}</td>
                                </tr>
                                @if( $sales->quotation_id )
                                    <tr >
                                        <th >{{__('common.quotation')}} REF:</th>
                                        <td >{{ $sales->quotation->ref }}</td>
                                    </tr>
                                @endif
                                <tr >
                                    <th >{{__('common.invoice_no')}}:</th>
                                    <td >{{ "INV-".$sales->id }}</td>
                                </tr>
                                <tr >
                                    <th >{{__('common.memo_no')}}:</th>
                                    <td >{{ $sales->memo_no }}</td>
                                </tr>
                                @if( $sales->chalan_no )
                                    <tr >
                                        <th >{{__('common.chalan_no')}}:</th>
                                        <td >{{ $sales->chalan_no }}</td>
                                    </tr>
                                @endif
                                <tr >
                                    <th >{{__('common.account')}}:</th>
                                    <td >{{ $sales->cash_or_bank->title }}</td>
                                </tr>
                                <tr >
                                    <th >{{__('common.payment_method')}}:</th>
                                    <td >{{ $sales->payment_method->name }}</td>
                                </tr>
                                @if( $sales->vehicle_number )
                                    <tr >
                                        <th >{{__('common.vehicle_number')}}:</th>
                                        <td >{{ $sales->vehicle_number }}</td>
                                    </tr>
                                @endif
                            </table>
                        </div>
                        @if($sales->customer)
                            <div style="border--left: 1px solid #d2d1d1;" class="col-md-push-3 col-md-4 p-5 ">
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
                    <!-- /.row -->

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
                                    <th>{{__('common.unit')}}</th>
                                    <th class="text-center">{{__('common.quantity')}}</th>
                                    <th class="text-center">{{__('common.price_per_qty')}}</th>
                                    <th class="text-center"> {{__('common.price')}}</th>
                                    <th class="text-center">{{__('common.discount')}}</th>
                                    <th class="text-right">{{__('common.total_price')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach( $sales->sale_details as $key=>$sale)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        {{--<td>{{ $sale->item }}</td>--}}
                                        <td>{{ $sale->item->item_name }}</td>
                                        <td>{{ $sale->product_info }}</td>
                                        <td>{{ $sale->unit }}</td>
                                        <td class="text-center">{{ $sale->qty }}</td>
                                        <td class="text-center">{{ create_money_format($sale->price) }}</td>
                                        <td class="text-center">{{create_money_format($sale->total_price) }}</td>
                                        <td class="text-center">{{ create_money_format($sale->discount) }}</td>
                                        <td class="text-right">{{ create_money_format($sale->total_price-$sale->discount) }}</td>
                                    </tr>

                                    @php
                                        $total += $sale->total_price;
                                    @endphp
                                @endforeach

                                <tr>
                                    <td colspan="4" rowspan="10"><b>{{__('common.notation')}}:</b> {{ $sales->notation }}</td>
                                    <td colspan="3" rowspan="10" >
                                        <h1 class="text-uppercase text-center"> {{ $sales->due> 0 ? trans('common.unpaid') : trans('common.paid') }}</h1>
                                    </td>
                                    <td class="text-right" >{{__('common.sub_total')}}:</td>
                                    <td class="text-right" >{{ create_money_format($total) }}</td>
                                </tr>
                                <tr>
                                    <td class="text-right" >{{__('common.discount')}} {{ $sales->discount_type=="fixed" ? "(Fixed)" : "(".$sales->discount."%)" }} :</td>
                                    <td class="text-right">(-) {{ create_money_format($sales->total_discount) }}</td>
                                </tr>

                                @if($sales->shipping_charge>0)
                                <tr>
                                    <td class="text-right" >{{__('common.shipping_charge')}}:</td>
                                    <td class="text-right">{{ create_money_format($sales->shipping_charge) }}</td>
                                </tr>
                                @endif

                                @if($sales->transport_cost>0)
                                <tr>
                                    <td class="text-right" >{{__('common.transport_cost')}}</td>
                                    <td class="text-right" > {{ create_money_format($sales->transport_cost) }} </td>
                                </tr>
                                @endif

                                @if($sales->unload_cost>0)
                                <tr>
                                    <td class="text-right" >{{__('common.unload_cost')}}</td>
                                    <td class="text-right" > {{ create_money_format($sales->unload_cost) }} </td>
                                </tr>
                                @endif

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
                                    <td class="text-right" >{{__('common.previous_due_amount')}}:</td>
                                    <td class="text-right"> {{ create_money_format($previousDue) }}</td>
                                </tr>
                                <tr>
                                    <th class="text-right" >{{__('common.total_due_amount')}}:</th>
                                    <th class="text-right"> {{ create_money_format($previousDue+$sales->due) }}</th>
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
                    <!-- /.col -->
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->

@endsection

