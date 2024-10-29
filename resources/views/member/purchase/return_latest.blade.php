<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/29/2019
 * Time: 3:51 PM
 */

 $route =  \Auth::user()->can(['member.purchase.index']) ? route('member.purchase.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

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
        'name' => 'Return',
    ],
];

$data['data'] = [
    'name' => 'Purchase Return',
    'title'=> 'Purchase Return',
    'heading' => trans('purchase.purchase_return'),
];

?>
@extends('layouts.back-end.master', $data)


@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush

@section('contents')

    <div class="box box-default">

        @include('common._alert')

        <div class="box-header with-border">
            <h3 class="box-title">{{__('common.return_purchase')}}</h3>
        </div>

        {!! Form::model($modal, ['route' => ['member.purchase_return.update', $modal],  'method' => 'put', 'role'=>'form', 'files'=>true,'id'=>'update']) !!}

        <div class="box-body">
            <div class="row">
                <div class="box">
                    <div class="box-body">
                        <div class="col-md-2">
                            <label for="suppliers"> {{__('common.date')}} </label>
                            {!! Form::text('date', month_date_year_format($modal->date),['id'=>'date', 'class'=>'form-control','required']); !!}
                        </div>
                        <div class="col-md-3">
                            <label for="supplier_id"> {{__('common.supplier_name')}} </label>
                            {!! Form::select('supplier_id', $suppliers, null,['id'=>'supplier_id', 'class'=>'form-control select2','required', 'placeholder'=>'Select Supplier Name', 'readonly']); !!}
                            <div id="current-balance">  </div>
                        </div>
                        <div class="col-md-2">
                            <label for="memo_no"> {{__('common.memo_no')}}. </label>
                            {!! Form::text('memo_no', $memo_no, [ 'class'=>'form-control','required', 'readonly']); !!}
                        </div>
                        <div class="col-md-2">
                            <label for="chalan_no"> {{__('common.chalan_no')}}. </label>
                            {!! Form::text('chalan', $chalan_no, ['class'=>'form-control','required', 'readonly']); !!}
                        </div>
                        <div class="col-md-3">
                            <label for="invoice_no"> {{__('common.invoice_no')}}. </label>
                            {!! Form::text('invoice_no', null, ['class'=>'form-control']); !!}
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12  new-table-responsive text-center">
                    <h4>{{__('purchase.purchase_order_item')}}</h4>

                    <table class="sales_table" id="items">


                        <thead>
                        <tr>
                            <th>{{__('common.item_name')}}</th>
                            <th>{{__('common.description')}}</th>
                            <th>{{__('common.available_stock')}}</th>
                            {{--<th>Last Purchase Qty</th>--}}
                            <th>{{__('common.unit')}}</th>
                            <th>{{__('common.purchase_qty')}}</th>
                            <th>{{__('common.purchase_price')}}</th>
                            <th>{{__('common.purchase_total_price')}}</th>
                            <th>{{__('common.return_qty')}}</th>
                            <th>{{__('common.return_price')}}</th>
                            <th>{{__('common.return_total_price')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($modal->purchase_details as $value)
                            <tr class="item-row">
                                <td class="item-name">
                                    <input type="hidden" value="{{ $value->id }}" name="Purchase_details_id[]">
                                    {!! Form::select('product_id[]', $products, $value->item_id,['id'=>'product_id_0', 'data-option'=>'0', 'class'=>'form-control select2 item-name','required', 'placeholder'=>trans('common.select_item_name'), "readonly"]); !!}
                                </td>
                                <td class="description" width="100px">
                                    {!! Form::text('description[]',$value->description,['class'=>'form-control']); !!}
                                </td>
                                <td>{!! Form::text('available_stock[]',$value->item->stock_details->stock,['id'=>'stock_0','class'=>'form-control', 'readonly']); !!}</td>
                                {{--<td>{!! Form::number('last_Purchase_qty[]',$value->last_Purchase_qty,['id'=>'last_Purchase_qty_0','class'=>'form-control', 'readonly']); !!}</td>--}}
                                <td>
                                    {!! Form::text('unit[]',$value->unit,['id'=>'unit_0','class'=>'form-control', 'disabled']); !!}
                                </td>
                                <td>{!! Form::number('qty[]',$value->qty,['id'=>'qty_0','class'=>'form-control qty', 'required', "readonly"]); !!}</td>
                                <td>{!! Form::number('price[]',$value->price,['id'=>'price_0','class'=>'form-control price', 'readonly', 'required']); !!}</td>
                                <td>{!! Form::number('total_price[]',$value->price*$value->qty,['id'=>'total_price_0','class'=>'form-control total_price', 'readonly']); !!}</td>
                                <td>{!! Form::number('return_qty[]',null,['id'=>'return_qty_0','class'=>'form-control qty', 'step'=>"any"]); !!}</td>
                                <td>{!! Form::number('return_price[]',null,['id'=>'return_price_0','class'=>'form-control price', 'step'=>"any"]); !!}</td>
                                <td>{!! Form::number('return_total_price[]', null,['id'=>'return_total_price_0','class'=>'form-control total_price', 'readonly']); !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>

            </div>

            <div style="margin-top: 20px; " class="row">
                <div  style="margin-bottom: 10px" class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right amount-info">
                    <table style="width: 100%" class="sales_table_2">
                        <tr>
                            <td  class="total-line">{{__('common.amount_to_pay')}}</td>
                            <td  class="total-value text-right">
                                {!! Form::number('amt_to_pay',null,['id'=>'amount_to_pay','class'=>'form-control input-number', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">{{__('common.paid_amount')}}</td>
                            <td  class="total-value text-right">
                                {!! Form::number('paid_amount',null,['id'=>'paid_amount','class'=>'form-control input-number', 'required', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">{{__('common.due_amount')}}</td>
                            <td  class="total-value text-right">
                                {!! Form::number('due_amount',null,['id'=>'due_amount','class'=>'form-control input-number', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">{{__('common.return_amount')}}</td>
                            <td  class="total-value text-right">
                                {!! Form::number('return_amount',null,['id'=>'return_amount','class'=>'form-control input-number text-bold', 'required', 'readonly']); !!}
                            </td>
                        </tr>

                    </table>

                </div>
                <div  class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right payment-info">
                    <table style="width: 100%" class="sales_table_2">

                        <tr>
                            <td  class="total-line ">{{__('common.account_name')}} </td>
                            <td  class="total-value">
                                {!! Form::select('account_type_id', $banks, null,['class'=>'form-control select2 ','required']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">{{__('common.comment')}} </td>
                            <td  class="total-value">
                                {!! Form::text('notation',null,['id'=>'notation','class'=>'form-control']); !!}
                            </td>
                        </tr>
                    </table>

                </div>

            </div>


            <div style="margin-top: 20px; margin-bottom: 20px" class="row pull-right">

                <div class="col-lg-12 col-md-12 ">
                    <table class="new-table-3">
                        <tr>
                            <td>
                                <button style="width: 100px" type="submit" class="btn btn-block btn-primary">{{__('common.save_return')}}</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            {!! Form::close() !!}


        </div>
    </div>

    @push('scripts')

        <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
        <script type="text/javascript">

            $(function () {
                $('#date').datepicker({
                    "setDate": new Date(),
                    "format": 'mm/dd/yyyy',
                    "endDate": "+0d",
                    "todayHighlight": true,
                    "autoclose": true
                });
                var today = moment().format('MM\DD\YYYY');
                $('#date').datepicker('setDate', today);
                $('#date').datepicker('update');
                $('.date').datepicker('setDate', today);

                $('.select2').select2();

            });
            $(document).on('keyup','.qty, .price', function(e) {
                e.preventDefault();
                price_calculate();
            });

            $(document).on('keyup','.qty', function(e) {
                e.preventDefault();

                var $div = $(this).parent().parent();
                var stock = $div.find('td:nth-child(6)').find('input').val();
                if(parseFloat(stock)<parseFloat($(this).val()))
                {
                    $(this).val('');
                    bootbox.alert("{{__('common.purchases_return_quantity_cant_cross_purchase_qty')}}");
                    return false;
                }
            });

            $(document).on('keyup','.price', function(e) {
                e.preventDefault();

                var $div = $(this).parent().parent();
                var price = $div.find('td:nth-child(7)').find('input').val();
                if(parseFloat(price)<parseFloat($(this).val()))
                {
                    $(this).val('');
                    $div.find('td:nth-child(11)').find('input').val('');
                    bootbox.alert("{{__('common.purchases_return_price_cant_cross_purchase_price')}}");
                    return false;
                }
            });

            function price_calculate(){

                var $tr = $('.sales_table tbody');
                var total_bill = 0;
                for(var i = 1; i<=$tr.find('tr').length; i++) {
                    var qty = $tr.find('tr:nth-child('+i+') td:nth-child(8) input').val();
                    var price = $tr.find('tr:nth-child('+i+') td:nth-child(9) input').val();
                    qty =  qty == undefined || qty == "" ? 0 : parseInt(qty);
                    price =  price == undefined || price == "" ? 0 : parseFloat(price);
                    var total_price = parseFloat(qty*price);
                    total_bill = total_bill+total_price;
                    $tr.find('tr:nth-child('+i+') td:nth-child(10) input').val(total_price);
                }
                $('#return_amount').val(total_bill);
            }

        </script>
    @endpush


@endsection

