<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/13/2019
 * Time: 4:16 PM
 */

$user = \Auth::user();

$route = \Auth::user()->can(['member.purchase.index']) ? route('member.purchase.index') : "#";
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Purchases',
        'href' => $route,
    ],
    [
        'name' => $purchase['memo_no'],
    ],
];

$data['data'] = [
    'name' => 'Purchase Memo no: ' . $purchase['memo_no'],
    'title' => 'Memo no: ' . $purchase['memo_no'],
    'heading' => trans('purchase.purchase_memo_no').': ' . $purchase['memo_no'],
];

?>


@extends('layouts.back-end.master', $data)

@section('contents')

    <div class="row text-right">
        <div class="col-md-12">


            @if(config('settings.warehouse')  && session('status'))

                <div class="box">
                    <div class="callout callout-info text-left">
                        <h4> {{__('purchase.load_to_warehouse')}}</h4>

                        <p> {{__('purchase.do_you_want_to_proceed_this_purchase')}} </p>
                        <a class="btn btn-default text-black text-bold "
                           href="{{ route('member.warehouse.load', $purchase->id) }}"> <i class="fa fa-arrow-left"> </i>
                            {{__('purchase.proceed_load')}} </a>
                    </div>
                </div>

            @endif


            <div class="box">
                <div class="box-body">
                    @if(config('settings.warehouse') && $user->can(['member.warehouse.load']))

                        <a class="btn btn-xs bg-purple" href="{{ route('member.warehouse.load', $purchase->id) }}"><i
                                class="fa fa-arrow-left"></i> {{__('purchase.load_warehouse')}}</a>

                    @endif

                    @if($user->can(['member.purchase.print_purchases']))
                        <a class="btn btn-xs btn-primary"
                           href="{{ route('member.purchase.print_purchases', $purchase->id) }}" id="btn-print"><i
                                class="fa fa-print"></i> {{__('common.print')}}</a>
                    @endif

                    @if($user->can(['member.purchase_return.edit']))
                        <a href="{{ route('member.purchase_return.edit', $purchase->id) }}" class="btn btn-xs btn-info"><i
                                class="fa fa-reply"></i> {{__('purchase.purchase_return')}}
                        </a>
                    @endif


                    @if($user->can(['member.purchase.edit']))
                        <a href="{{ route('member.purchase.edit',  $purchase->id) }}" class="btn btn-xs btn-success"> <i
                                class="fa fa-pencil"></i> {{__('common.Edit')}}
                        </a>
                    @endif


                    @if($user->can(['member.purchase.create']))
                        <a href="{{ route('member.purchase.create') }}" class="btn btn-xs btn-warning"><i
                                class="fa fa-plus"></i> {{__('common.add_purchase')}}</a>
                    @endif


                    @if($user->can(['member.purchase.destroy']))
                        <a href="javascript:void(0);" class="btn btn-xs btn-danger delete-confirm"
                           data-target="{{route('member.transaction.destroy', $purchase->transaction->id)}}">
                            <i class="fa fa-trash"></i> {{__('common.Delete')}}
                        </a>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">

                <div class="box-body">

                    <div class="row">
                        <div class="col-xs-4 text-left">
                            @if( isset($company_logo))
                                <img src="{{ $company_logo }}" width="100px;"/>
                            @endif
                        </div>
                        <div class="col-xs-4 text-center py-5">
                            @php print_r($purchase_barcode) @endphp<br>
                        </div>
                        <div class="col-xs-4 text-right company-info">
                            <h2 style="margin: 10px auto!important">{{ $company_name }}</h2>
                            <div class="info">
                                <p>{{ $company_address }} </p>
                                <p>{{ $company_city.($company_country ? ", ".$company_country : "") }}</p>
                                <p>{{__('common.phone')}} : {{ $company_phone }}</p>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- title row -->

                    <!-- info row -->
                    <div style="margin-bottom: 10px; " class="row invoice-info">

                        <div class="col-md-6 p-5">
                            <table class="bill-info w-100">
                                <tr>
                                    <th>{{__('common.date')}}:</th>
                                    <td>{{ $purchase->date_format }}</td>
                                </tr>
                                @if( $purchase->quotation_id )
                                    <tr>
                                        <th>{{__('purchase.quotation_ref')}}:</th>
                                        <td>{{ $purchase->quotation->ref }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>{{__('common.invoice_no')}}:</th>
                                    <td>{{ $purchase->memo_no }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('common.memo_no')}}:</th>
                                    <td>{{ $purchase->invoice_no }}</td>
                                </tr>
                                @if( $purchase->chalan_no )
                                    <tr>
                                        <th>{{__('common.chalan_no')}}:</th>
                                        <td>{{ $purchase->chalan_no }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th>{{__('common.account')}}:</th>
                                    <td>{{ $purchase->cash_or_bank->title }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('common.payment_method')}}:</th>
                                    <td>{{ $purchase->payment_method->name }}</td>
                                </tr>
                                {{--                                @if( $purchase->vehicle_number )--}}
                                <tr>
                                    <th>{{__('common.vehicle_number')}}:</th>
                                    <td>{{ $purchase->vehicle_number }}</td>
                                </tr>
                                {{--                                @endif--}}
                            </table>
                        </div>
                        @if($purchase->supplier)
                            <div style="border--left: 1px solid #d2d1d1;" class="col-md-push-2 col-md-4 p-5 ">
                                <h4>{{__('common.supplier_info')}}:</h4>
                                <table class="w-100 customer-table-info">
                                    <tr>
                                        <th class="w-25">{{__('common.name')}}:</th>
                                        <td class="w-75"> {{ $purchase->supplier->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="w-25">{{__('common.address')}}:</th>
                                        <td class="w-75">{{ $purchase->supplier->address }}</td>
                                    </tr>
                                    <tr>
                                        <th class="w-25">{{__('common.phone')}}:</th>
                                        <td class="w-75">{{ $purchase->supplier->phone }}</td>
                                    </tr>
                                </table>
                            </div>
                        @endif
                    </div>
                    <!-- /.row -->


                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive" id="custom-print">

                            <table class="table table-responsive table-bordered margin-top-30 float-right">
                                <tbody>
                                <tr>
                                    <th>{{__('common.item_name')}}</th>
                                    <th>{{__('common.description')}}</th>
                                    <th>{{__('common.unit')}}</th>
                                    <th>{{__('common.qty')}}</th>
                                    <th>{{__('common.price')}}</th>
                                    <th class="text-center">{{__('common.total_price')}}</th>
                                </tr>
                                @php $total = 0; @endphp
                                @foreach($purchase->purchase_details as $value)
                                    <tr>
                                        <td>{{ $value->item->item_name }}</td>
                                        <td>{{ $value->description }}</td>
                                        <td>{{ $value->unit }}</td>
                                        <td> {{ $value->qty }}</td>
                                        <td> {{ create_money_format($value->price) }}</td>
                                        <td class="text-right">{{ create_money_format($value->total_price) }}</td>
                                    </tr>
                                    @php
                                        $total += $value->total_price;
                                    @endphp
                                @endforeach
                                </tbody>

                            </table>
                            <table class="margin-top-30" style="width: 700px; float: left;">
                                <tr>
                                    <th> {{__('common.notes')}}:</th>
                                </tr>
                                <tr>
                                    <td>
                                        @php print_r($purchase->notation) @endphp
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        @if($purchase->file)
                                            <a href="{{ $purchase->attach_file_path }}" target="_blank"
                                               class="text-bold"> {{__('common.check_your_attached_file')}}</a>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            <table class=" margin-top-30 pull-right" width="400px">

                                <tr>
                                    <th class="text-right" colspan="5"> {{__('common.sub_total')}}</th>
                                    <th class="text-right"> {{ create_money_format($total) }} </th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">{{__('common.transport_cost')}}</td>
                                    <td class="text-right"> {{ create_money_format($purchase->transport_cost) }} </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">{{__('common.unload_cost')}}</td>
                                    <td class="text-right"> {{ create_money_format($purchase->unload_cost) }} </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">{{__('common.bank_charge')}}</td>
                                    <td class="text-right"> {{ create_money_format($purchase->bank_charge) }} </td>
                                </tr>
                                <tr>
                                    <th class="text-right" colspan="5">{{__('common.grand_total')}}</th>
                                    <th class="text-right"> {{ create_money_format($purchase->total_amount) }} </th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">{{__('common.discount')}}
                                        ({{ $purchase->discount_type == "Percentage" ? $purchase->discount."%" : $purchase->discount_type }}
                                        )
                                    </td>
                                    <td class="text-right"> {{ $purchase->total_discount > 0 ? "- (".create_money_format($purchase->total_discount).")" : create_money_format(0.00) }} </td>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">{{__('common.amount_to_pay')}}</td>
                                    <td class="text-right"> {{ create_money_format($purchase->amt_to_pay) }} </td>
                                </tr>
                                <tr>
                                    <th class="text-right" colspan="5">{{__('common.paid_amount')}}</th>
                                    <th class="text-right"> {{ create_money_format($purchase->paid_amount) }} </th>
                                </tr>
                                <tr>
                                    <td class="text-right" colspan="5">{{__('common.due_amount')}}</td>
                                    <td class="text-right"> {{ create_money_format($purchase->due_amount) }} </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
