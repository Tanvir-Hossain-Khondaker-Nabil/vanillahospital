<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/20/2019
 * Time: 1:58 PM
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
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Create sale',
    'title'=> 'Create sale',
    'heading' => trans('sale.create_sale'),
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
            <h3 class="box-title">{{__('sale.whole_sale')}}</h3>
        </div>

        {!! Form::open(['route' => 'member.sales.whole_sale_store','method' => 'POST', 'files'=>true, 'id'=>'sale_form', 'role'=>'form' ]) !!}

        <div class="box-body">
            <div class="row">
                <div class="col-md-2 grid-width-20">
                    <div class="form-group">
                        <label for="inputPassword" >{{__('common.order_date')}}</label>
                        {{--<div class="col-sm-8">--}}
                        <input type="text" id="date" class="form-control" name="date">
                        {{--</div>--}}
                    </div>
                </div>

                <div class="col-md-2 grid-width-20">
                    <div class="form-group ">
                        <label for="inputPassword" >{{__('common.customer_name')}}</label>
                        {{--<div class="col-sm-8">--}}
                        {!! Form::select('customer_id', $customers, null,['id'=>"customer_id", 'class'=>'form-control select2', 'placeholder'=>trans('common.select_customer')]); !!} </br>
                        <button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#addCustomer" id="create_cus"> <i class="fa fa-plus-circle"></i> {{__('common.add_customer')}} </button>
                        {{--</div>--}}
                    </div>
                </div>

                <div class="col-md-2 grid-width-20">
                    <div class="form-group ">
                        <label for="inputPassword" >{{__('sale.last_credit_amount')}}</label>
                        {{--<div class="col-sm-8">--}}
                        <input type="text" class="form-control" id="last_credit" value="" readonly>
                        {{--</div>--}}
                    </div>
                    <!-- /.form-group -->
                </div>

                <div class="col-md-2 grid-width-20">
                    <div class="form-group ">
                        <label for="inputPassword" >{{__('sale.membership_card_no')}}</label>
                        {{--<div class="col-sm-8">--}}
                        <input type="text" class="form-control" id="inputPassword" name="membership_card" placeholder="">
                    </div>
                </div>


                <div class="col-md-2 grid-width-20">
                    <div class="form-group ">
                        <label for="inputPassword" >{{__('sale.product_barcode_search')}}</label>
                        {{--<div class="col-sm-9">--}}
                        <input type="text" id="barcode_search" class="form-control" name="barcode_search">
                        {{--</div>--}}
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group" id="customer_details">

                </div>
            </div>

            {{--<div class="col-md-5 col-form-label">--}}
            {{--<button type="button" class="btn btn-primary">Product Search</button>--}}
            {{--</div>--}}
        </div>
        <!-- /.row -->
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12  new-table-responsive text-center">
                <h4>{{__('sale.sales_order_item')}}</h4>

                <table class="sales_table" id="items">


                    <thead>
                    <tr>
                        <th>{{__('common.product')}}</th>
                        {{--<th>Description</th>--}}
                        <th>{{__('common.available_stock')}}</th>
                        <th>{{__('sale.last_sales_qty')}}</th>
                        <th>{{__('common.unit')}}</th>
                        <th>{{__('common.qty')}}</th>
                        <th>{{__('common.price_per_qty')}}</th>
                        <th>{{__('common.total_price')}}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="item-row">
                        <td class="item-name">
                            {!! Form::select('product_id[]', $products, null,['id'=>'product_id_0', 'data-option'=>'0', 'class'=>'form-control select2 item-name','required', 'placeholder'=>trans('common.select_item_name')]); !!}
                        </td>
                        {{--<td class="description">--}}
                            {{--{!! Form::text('description[]', null,[ 'class'=>'form-control ']); !!}--}}
                        {{--</td>--}}
                        <td>{!! Form::text('available_stock[]',null,['id'=>'stock_0','class'=>'form-control', 'readonly']); !!}</td>
                        <td>{!! Form::number('last_sale_qty[]',null,['id'=>'last_sale_qty_0','class'=>'form-control', 'readonly']); !!}</td>
                        <td>
                            {!! Form::text('unit[]',null,['id'=>'unit_0','class'=>'form-control', 'disabled']); !!}
                        </td>
                        <td>{!! Form::number('qty[]',null,['id'=>'qty_0','class'=>'form-control qty',  'step'=>"any", 'required']); !!}</td>
                        <td>{!! Form::number('price[]',null,['id'=>'price_0','class'=>'form-control price',  'step'=>"any", 'readonly']); !!}</td>
                        <td>{!! Form::number('total_price[]',null,['id'=>'total_price_0','class'=>'form-control total_price', 'step'=>"any", 'required' ]); !!}</td>
                        <td><a class="btn btn-success add-row pull-right margin-top-3" href="#" ><i class="fa fa-plus-circle"></i> </a></td>
                    </tr>
                    </tbody>
                </table>

            </div>

        </div>

        <div style="margin-top: 20px; " class="row">
            <div  style="margin-bottom: 10px" class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right amount-info">
                <table style="width: 100%" class="sales_table_2">
                    <tr>
                        <td  class="total-line">{{__('common.sub_total')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('sub_total',0,['id'=>'sub_total','class'=>'form-control input-number', 'readonly', 'step'=>"any"]); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.transport_cost')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('transport_cost',0,['id'=>'transport_cost','class'=>'form-control input-number', 'step'=>"any"]); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.labor_cost')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('labor_cost',0,['id'=>'labor_cost','class'=>'form-control input-number', 'step'=>"any"]); !!}
                        </td>
                    </tr>
                    <tr>
                        <td class="total-line">{{__('common.discount_type')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::select('discount_type', ['fixed'=>'Fixed', 'percentage'=>'Percentage'], null,['id'=>'discount_type','class'=>'form-control']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td class="total-line">{{__('common.discount')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('discount',0,['id'=>'discount', 'step'=>"any", 'class'=>'form-control input-number']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.total_amount')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('total_amount',0,['id'=>'total_amount','class'=>'form-control input-number', 'step'=>"any", 'readonly']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.amount_to_pay')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('amount_to_pay',0,['id'=>'amount_to_pay','class'=>'form-control input-number', 'step'=>"any", 'readonly']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.paid_amount')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('paid_amount',0,['id'=>'paid_amount','class'=>'form-control input-number',  'step'=>"any",'required']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line">{{__('common.due_amount')}}</td>
                        <td  class="total-value text-right">
                            {!! Form::number('due',0,['id'=>'due_amount','class'=>'form-control input-number',  'step'=>"any", 'readonly']); !!}
                        </td>
                    </tr>

                </table>

            </div>
            <div  class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right payment-info">
                <table style="width: 100%" class="sales_table_2">

                    <tr>
                        <td  class="total-line ">{{__('common.payment_option')}} </td>
                        <td  class="total-value">
                            <input type="checkbox" id="payment_option"  name="payment_option" value="1">   {{__('common.due_credit')}}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line ">{{__('common.account_name')}} </td>
                        <td  class="total-value">
                            {!! Form::select('cash_or_bank_id', $banks, config('settings.cash_bank_id'),['class'=>'form-control select2 ','required']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td   class="total-line ">{{__('common.memo_no')}} </td>
                        <td  class="total-value">
                            {!! Form::text('memo_no',null,['id'=>'memo_no','class'=>'form-control']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line ">{{__('common.chalan_no')}} </td>
                        <td  class="total-value">
                            {!! Form::text('chalan_no',null,['id'=>'chalan_no','class'=>'form-control']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td  class="total-line ">{{__('common.vehicle_number')}} </td>
                        <td  class="total-value">
                            {!! Form::text('vehicle_number',null,['id'=>'vehicle_number','class'=>'form-control']); !!}
                        </td>
                    </tr>
                    <tr>
                        <td   class="total-line ">{{__('common.payment_method')}} </td>
                        <td  class="total-value">
                            {!! Form::select('payment_method_id', $payment_methods, null,['class'=>'form-control select2 ','required']); !!}
                        </td>
                    </tr>


                    @if(config('settings.online_sale_track'))
                        <tr>
                            <td   class="total-line ">{{__('common.online_sale')}} </td>
                            <td  class="total-value">
                                {!! Form::select('online_sale', [0=>"No", 1=>"Yes"], null,['class'=>'form-control ','required']); !!}
                            </td>
                        </tr>
                    @endif

                    @if(config('settings.courier_settings'))
                        <tr>
                            <td   class="total-line "> {{__('common.delivery_by_courier')}} </td>
                            <td  class="total-value">
                                {!! Form::select('delivery_by_courier', [0=>"No", 1=>"Yes"], null,['class'=>'form-control ','required']); !!}
                            </td>
                        </tr>
                    @endif
                    <tr class="hidden">

                        <td class="total-line ">{{__('common.delivery_system')}} </td>
                        <td  class="total-value">
                            {!! Form::select('delivery_type_id', $delivery_types, null,['class'=>'form-control select2 ','required']); !!}
                        </td>
                    </tr>
                    <tr class="hidden">
                        <td  class="total-line " colspan="2">
                            <label> {{__('common.shopping_bags')}}</label><br/>
                            @foreach($bags as $value)
                                <div class="col-md-4" style="padding-left: 5px; padding-right: 5px;"><label id="bags_{{$value->id}}"> {{ $value->item_name }}</label>
                                    {!! Form::number("shopping_bags_".$value->id,null,['class'=>'form-control bags_qty','data-option'=>$value->id]); !!}</div>
                            @endforeach

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

            <div class="col-lg-12 col-md-12 ">
                <table class="new-table-3  pull-right" style="margin-top: 20px; margin-bottom: 20px">
                    <tr>
                        <td>
                            <button style="width: 100px" type="reset" class="btn btn-block btn-warning">{{__('common.cancel')}}</button>
                        </td>
                        <td>
                            <button style="width: 100px" type="submit" class="btn btn-block btn-primary">{{__('common.save')}}</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>


        {!! Form::close() !!}


    </div>


    @include('member.sales._model_add_customer')

    @push('scripts')

        <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
        <!-- CK Editor -->
        <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

        <script type="text/javascript">

            var editItem = '';

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


                var products = [];
                products = <?php print_r(json_encode($products)); ?>;



                var i = 1;
                $(document).on('click', '.add-row', function () {
                    var optionName = '';
                    var $div = $(this).parent().parent();
                    var childrenTd = $div.children();
                    if($div.find('td:first-child').find('select').val() == ""  || $div.find('td:nth-child(6)').find('input').val() == "")
                    {
                        bootbox.alert("{{__('common.please_select_item_name_or_input_value_qyt_price')}}");
                        return false;
                    }

                    var itemId = $div.find('td:first-child').find('select').val();
                    delete products[itemId];

                    optionName='<option selected="selected" value="">{{__("common.select_item_name")}}</option>';
                    $.each( products, function( key, value ) {
                        optionName = optionName+'<option value="'+key+'" >'+value+'</option>';
                    });

                    var html = '<tr class="item-row">' +
                        '<td class="item-name">' +
                        editItem +
                        '<select id="product_id_'+i+'" data-option="'+i+'" class="form-control select2 item-name" name="product_id[]" >'+optionName+'</select>' +
                        '</td>' +
                        {{--'<td class="description">' +--}}
                        {{--'{!! Form::text('description[]', null,[ 'class'=>'form-control ']); !!}' +--}}
                        {{--'</td>' +--}}
                        '<td><input id="stock_'+i+'" class="form-control"  name="available_stock[]" readonly="" type="text"></td>' +
                        '<td><input id="last_sale_qty_'+i+'" class="form-control" name="last_sale_qty[]" readonly="" type="number" min="0"></td>\n' +
                        '<td>' +
                        '<input id="unit_'+i+'" class="form-control" name="unit[]" readonly="" type="text">' +
                        '</td>' +
                        '<td><input id="qty_'+i+'" class="form-control qty" step="any" name="qty[]" type="number" min="0.00"></td>' +
                        '<td><input id="price_'+i+'" class="form-control price" step="any" name="price[]" type="number" min="0.00" readonly=""></td>' +
                        '<td><input id="total_price_'+i+'" class="form-control total_price" step="any" name="total_price[]" type="number" min="0.00" ></td>' +
                        '<td><a class="btn btn-success add-row pull-right margin-top-3" href="#"><i class="fa fa-plus-circle"></i> </a></td>' +
                        '</tr>';

                    i++;

                    $(".sales_table tbody").append(html);

                    // $(this).parent().css("padding-top", '10px');
                    childrenTd.find('input').attr('readonly', true);
                    childrenTd.find('input').attr('required', true);
                    $div.find('td:nth-child(2)').find('input').attr('required', false);
                    childrenTd.find('select').attr('readonly', true);
                    childrenTd.find('select').attr('required', true);
                    var actionHtml = '<a href="#" class="btn-text-info margin-top-3 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a></span>';
                    $(this).parent('td').html(actionHtml);
                    //
                    // $('input[type=number]').attr('min', 0);
                    // $div.append(html);
                    $('.select2').select2();

                });


                $("#payment_option").change( function () {

                    if(this.checked)
                    {
                        $('#discount_type').attr('readonly', true);
                        $('#paid_amount').attr('readonly', true);
                        $('#paid_amount').val(0);
                        $('#discount').attr('readonly', true);
                    }else{
                        $('#discount_type').attr('readonly', false);
                        $('#paid_amount').attr('readonly', false);
                        $('#discount').attr('readonly', false);
                    }

                });

                $(document).on('click','.edit-button', function() {
                    var $mainDiv = $(this).parent().parent().parent().children();
                    $mainDiv.find('input').attr('readonly', true);
                    $mainDiv.find('select').attr('readonly', true);

                    var $div = $(this).parent().parent();
                    var childrenDiv = $div.children();
                    childrenDiv.find('input').attr('readonly', false);
                    childrenDiv.find('select').attr('readonly', false);
                    $div.find('td:nth-child(7) input').attr('readonly', true);
                    $(this).parent().css("padding-top", '0px');
                    var actionHtml = '<a class="btn btn-warning pull-right margin-top-3" id="edit-complete" href="#" ><i class="fa fa-check-circle"></i> </a>';
                    $(this).parent('td').html(actionHtml);
                });

                $('#barcode_search').keypress(function(event){
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if(keycode == '13') {
                        var productCode = $('#barcode_search').val();
                        var $tr = $('.sales_table tbody');

                        var lastRow = $tr.find('tr').last();
                        if (lastRow.find('td:first-child').find('select').val() != ""){
                            $( ".add-row" ).trigger( "click" );
                            lastRow = $tr.find('tr').last();
                        }

                        var id = lastRow.find('td:first-child').find('select').data('option');

                        var form_data = {
                            '_token' : "{{ csrf_token() }}",
                            'product_code' : productCode,
                        };

                        item_details(form_data, id);

                        $('#barcode_search').val('');

                    }


                    e.stopImmediatePropagation();
                    return false;
                });

                $('.bags_qty').change(function(){
                    var url = "{{ route('search.sale_bags') }}";
                    var productId = $(this).data('option');
                    var qty = $(this).val();

                    var form_data = {
                        '_token' : "{{ csrf_token() }}",
                        'item_id' : productId,
                    };

                    $.ajax({
                        type        : 'POST',
                        url         : url, // the url where we want to POST
                        data        : form_data,
                        dataType    : 'json',
                        encode      : true
                    })
                    // using the done promise callback
                        .done(function(data) {
                            // console.log(data);
                            if(data.status == "success")
                            {
                                if(data.stock-qty>0)
                                {
                                    var label_text = $('#bags_'+productId).text();
                                    label_text = data.item_name+" ("+(data.stock-qty)+")";
                                    $('#bags_'+productId).text(label_text);
                                }else{
                                    $('#bags_'+productId).css("color",'red');
                                    var label_text = $('#bags_'+productId).text();
                                    label_text = data.item_name+" ({{__('common.out_of_stock')}})";
                                    $('#bags_'+productId).text(label_text);
                                }

                            }else{
                                bootbox.alert("{{__('common.no_data_found')}}");
                            }
                        });

                });

                $(document).on('click','#edit-complete', function() {

                    var $div = $(this).parent().parent();
                    var childrenDiv = $div.children();
                    childrenDiv.find('input').attr('readonly', true);
                    childrenDiv.find('select').attr('readonly', true);
                    var $mainDiv = $(this).parent().parent().parent().children();
                    // console.log($mainDiv.last().children().last());
                    $mainDiv.last().find('input').attr('readonly', false);
                    $mainDiv.last().find('td:nth-child(7) input').attr('readonly', true);
                    $mainDiv.last().find('select').attr('readonly', false);
                    var actionHtml = '<a href="#" class=" btn-text-info margin-top-23 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a>';
                    $(this).parent('td').html(actionHtml);
                });

                $(document).on('click','.delete-field', function(e) {
                    e.preventDefault();
                    var $div = $(this).parent().parent();
                    $div.remove();
                    total_price_calculate();
                });


                $(document).on('keyup','.qty, .total_price', function(e) {
                    e.preventDefault();
                    total_price_calculate();
                });

                $(document).on('keyup','.qty', function(e) {
                    e.preventDefault();

                    var $div = $(this).parent().parent();
                    var stock = $div.find('td:nth-child(2)').find('input').val();
                    if(parseFloat(stock)<$(this).val())
                    {
                        $(this).val('');
                        bootbox.alert("{{__('common.sales_quantity_cant_cross_available_stock')}}");
                        return false;
                    }
                });

                $(document).on('keyup','#labor_cost, #transport_cost, #discount, #paid_amount', function(e) {
                    e.preventDefault();

                    total_price_calculate();
                });

                $(document).on('change','#discount_type', function(e) {
                    e.preventDefault();
                    total_price_calculate();
                });

                function price_calculate(){

                    var $tr = $('.sales_table tbody');
                    var total_bill = 0;
                    for(var i = 1; i<=$tr.find('tr').length; i++) {
                        var qty = $tr.find('tr:nth-child('+i+') td:nth-child(5) input').val();
                        // var price = $tr.find('tr:nth-child('+i+') td:nth-child(6) input').val();
                        var total_price = $tr.find('tr:nth-child('+i+') td:nth-child(7) input').val();
                        qty =  qty == undefined || qty == "" ? 0 : parseFloat(qty);
                        // price =  price == undefined || price == "" ? 0 : parseFloat(price);
                        total_price =  total_price == undefined || total_price == "" ? 0 : parseFloat(total_price);

                        var price = total_price/qty;
                        $tr.find('tr:nth-child('+i+') td:nth-child(6) input').val(price.toFixed(2));

                        total_bill = total_bill+total_price;
                        // alert(total_bill);
                    }
                    $('#sub_total').val(total_bill);
                }

                function total_price_calculate()
                {
                    price_calculate();

                    var total_bill = $('#sub_total').val();
                    // var shipping_charge = parseFloat($('#shipping_charge').val());
                    var labor_cost = parseFloat($('#labor_cost').val());
                    var transport_cost = parseFloat($('#transport_cost').val());
                    var discount_type = $('#discount_type').val();
                    var discount = $('#discount').val();
                    var discount_amount = 0;

                    var paid_amount = $('#paid_amount').val();

                    paid_amount = paid_amount == '' ? 0 : paid_amount;

                    total_bill = parseFloat(total_bill);
                    paid_amount = parseFloat(paid_amount);
                    discount = parseFloat(discount);

                    var total_amount = total_bill+labor_cost+transport_cost;


                    if(discount_type=="fixed")
                        discount_amount = discount;
                    else
                        discount_amount = total_bill*discount/100;

                    var amount_to_pay = total_amount-discount_amount;
                    var due = amount_to_pay-paid_amount;

                    if(due>0)
                        $('#customer_id').attr('required', true);
                    else
                        $('#customer_id').attr('required', false);

                    $('#total_amount').val(total_amount);
                    $('#amount_to_pay').val(amount_to_pay);
                    $('#due_amount').val(due);
                }


                $("#customer_id").change( function(e){
                    e.preventDefault();
                    var url = "{{ route('search.customer_info') }}";
                    var customer_id = $(this).val();

                    var form_data = {
                        '_token' : "{{ csrf_token() }}",
                        'customer_id' : customer_id
                    };

                    $.ajax({
                        type        : 'POST',
                        url         : url, // the url where we want to POST
                        data        : form_data,
                        dataType    : 'json',
                        encode      : true
                    }).done(function(data) {
                        console.log(data);
                        if(data.status == "success")
                        {
                            var credit = data.last_sale_amount != null ? data.last_sale_amount.total_price : 0;
                            $('#last_credit').val(credit);
                            var html = "<label> {{__('common.customer_name')}}:</label> "+data.customer.name+"<br/>"+"<label> {{__('common.address')}}: </label> "+data.customer.address+"<br/>"+"<label> {{__('common.phone')}}: </label> "+data.customer.phone+"<br/>"
                            $('#customer_details').html(html);
                        }else{
//                        console.log(data);
                            bootbox.alert("{{__('common.no_data_found')}}");
                        }
                    });
                });

                var url = "{{ route('search.sale_item_details') }}";
                $(document).on('change', '.item-name', function(e){
                    e.preventDefault();

                    var itemId = $(this).val();
                    var id = $(this).data('option');

                    var form_data = {
                        '_token' : "{{ csrf_token() }}",
                        'item_id' : itemId,
                    };

                    item_details(form_data, id);


                    e.stopImmediatePropagation();
                    return false;
                });

                function item_details(form_data, id) {

                    $.ajax({
                        type        : 'POST',
                        url         : url, // the url where we want to POST
                        data        : form_data,
                        dataType    : 'json',
                        encode      : true
                    })
                    // using the done promise callback
                        .done(function(data) {
                            // console.log(data);
                            if(data.status == "success")
                            {
                                if(data.stock>0)
                                {
                                    if(products[data.id]  !== undefined)
                                    {
                                        $('#product_id_'+id).val(data.id).trigger('change.select2');
                                        $('#stock_'+id).val(data.stock);
                                        $('#unit_'+id).val(data.unit);
                                        $('#price_'+id).val(data.price != null ? data.price : 0);
                                        $('#last_sale_qty_'+id).val(data.last_sale_qty != null ? data.last_sale_qty : 0);
                                    }else{
                                        bootbox.alert(data.item_name+" {{__('common.already_available_in_sells_or_not_available')}}");
                                    }

                                }else{
                                    bootbox.alert(data.item_name+" {{__('common.out_of_stock')}} ");
                                }


                            }else{
                                bootbox.alert("{{__('common.no_data_found')}}");
                            }
                        });
                }


                $("#paid_amount").keyup( function (e) {
                    e.preventDefault();

                    var price = parseFloat($(this).val());
                    var total_amount = parseFloat($("#amount_to_pay").val());

                    if(price>total_amount){
                        bootbox.alert("{{__('common.paid_amount_cant_be_bigger_than_amount_to_pay')}}");
                        $(this).val('');
                        return false;
                    }else{
                        total_price_calculate();
                    }
                });

                $('#save-customer').click(function (e) {
                    var url = "{{ route('member.customer.save') }}";
                    var form_data = {
                        '_token': '{{ csrf_token() }}',
                        'name' : $('#name').val(),
                        'phone' : $('#phone').val(),
                        'address' : $('#address').val(),
                        'email' : $('#email').val()
                    };

                    $.ajax({
                        type        : 'POST',
                        url         : url, // the url where we want to POST
                        data        : form_data,
                        dataType    : 'json',
                        encode      : true,
                    }).done(function(data) {

                        if(data.status==1){
                            console.log(data.values.id);
                            $("#customer_id").append('<option value="'+data.values.id+'" selected>'+data.values.name+'('+data.values.phone+')</option>');
                            $("#customer_id").trigger('change.select2');

                            $('#name').val('');
                            $('#phone').val('');
                            $('#email').val('');
                            $('#address').val('');
                            $('#addCustomer').modal('toggle');
                        }else{
                            bootbox.alert("{{__('common.unable_to_save')}}");
                        }
                        console.log(data);

                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        var error = $.parseJSON(jqXHR.responseText);
                        // console.log(error);
                        bootbox.alert("{{__('common.name_phone_is_required_Unique_and_Address_Required')}}");
                        // alert(textStatus);
                        // alert(errorThrown);
                    });

                    e.preventDefault();
                });
            });

        </script>
    @endpush


@endsection
