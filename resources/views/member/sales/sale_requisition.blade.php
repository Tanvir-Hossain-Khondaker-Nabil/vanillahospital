<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/22/2019
 * Time: 11:29 AM
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
    'name' => 'Create sales',
    'title'=> 'Create sales',
    'heading' => 'Create Sale',
];
$sub_total = 0;
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
            <h3 class="box-title"> Sale Order by Requisition</h3>
        </div>

        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['dealer']))

            {!! Form::open(['route' => 'member.dealer_sales.store','method' => 'POST', 'files'=>true, 'id'=>'sale_form', 'role'=>'form' ]) !!}

        @else

            {!! Form::open(['route' => 'member.sales_by_requisition.store','method' => 'POST', 'files'=>true, 'id'=>'sale_form', 'role'=>'form' ]) !!}

        @endif

        <div class="box-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group ">
                        <label for="inputPassword" class=" col-form-label">Order Date</label>
                        {!! Form::hidden('requisition_id',$model->id); !!}
                        {{--                        <div class="col-sm-8">--}}
                        {!! Form::text('date',create_date_format($model->date, '/'),['id'=>'date','class'=>'form-control']); !!}
                        {{--                        </div>--}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group ">
                        <label for="inputPassword" class="col-form-label">Customer Name</label>
                        {{--                        <div class="col-sm-8">--}}
                        @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['dealer']))

                            {!! Form::select('customer_id', $customers, $model->customer_id,['id'=>"customer_id", 'class'=>'form-control select2','selected']); !!} <br/>
                            <input type="hidden" name="saler_id" value="{{ $model->created_by }}">
                        @elseif(\Illuminate\Support\Facades\Auth::user()->hasRole(['sales_man']))
                            {!! Form::select('customer_id', $customers, $model->dealer->sharer->id,['id'=>"customer_id", 'class'=>'form-control select2', 'placeholder'=>'Select Customer']); !!} <br/>
                        @else
                            {!! Form::select('customer_id', $customers, null,['id'=>"customer_id", 'class'=>'form-control select2', 'placeholder'=>'Select Customer']); !!} <br/>
                        @endif
                        {{--                            <button class="btn btn-primary btn-xs" type="button" data-toggle="modal" data-target="#addCustomer"> <i class="fa fa-plus-circle"></i> Add Customer </button>--}}
                        {{--                        </div>--}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group ">
                        <label for="inputPassword" class="col-form-label">Last Credit Amount</label>
                        {{--                        <div class="col-sm-8">--}}
                        {!! Form::text('last_credit',null,['id'=> 'last_credit','class'=>'form-control','readonly']); !!}
                        {{--                        </div>--}}
                    </div>
                    <!-- /.form-group -->
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="inputPassword" class="col-form-label">Membership Card No</label>
                        {{--                        <div class="col-sm-8">--}}
                        {!! Form::text('membership_card',null,['class'=>'form-control']); !!}
                        {{--                        </div>--}}
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group" id="customer_details">

                    </div>
                </div>
            </div>
            <!-- /.row -->
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12  new-table-responsive text-center">
                    <h4>Sales Order Item</h4>

                    <table class="sales_table" id="items">


                        <thead>
                        <tr>
                            <th>Product</th>
                            <th>Description</th>
                            <th>Available Stock</th>
                            <th>Unit</th>
                            <th>Qty</th>
                            {{--<th>Carton</th>--}}
                            {{--<th>Free</th>--}}
                            <th> Per Qty Price</th>
                            <th>Total Price</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($model->sales_requisition_details as $key=>$sale)

                            @if(\Illuminate\Support\Facades\Auth::user()->hasRole(['dealer']))
                                @php
                                    $avail_stock = $sale->dealer_stock($model->dealer_id, $sale->item_id);
                                @endphp
                            @else
                                @php
                                    $avail_stock = $sale->item->stock_details ? $sale->item->stock_details->stock : 0;
                                @endphp
                            @endif
                            <tr class="item-row">
                                <td class="item-name">
                                    {!! Form::select('product_id[]', $products, $sale->item_id,['id'=>'product_id_'.$key, 'data-option'=>'0', 'class'=>'form-control select2 item-name','required', 'placeholder'=>'Select Item Name', !$loop->last ? "readonly":'']); !!}
                                </td>
                                <td class="description">
                                    {!! Form::text('description[]',$sale->description,['class'=>'form-control']); !!}
                                </td>
                                <td>{!! Form::text('available_stock[]',$avail_stock,['id'=>'stock_'.$key,'class'=>'form-control', 'readonly']); !!}</td>
                                <td>
                                    {!! Form::text('unit[]',$sale->unit,['id'=>'unit_'.$key,'class'=>'form-control', 'disabled']); !!}
                                </td>
                                <td>{!! Form::number('qty[]',$sale->qty,['id'=>'qty_'.$key,'class'=>'form-control input-number qty',  'step'=>"any", 'required', 'data-target'=>$key]); !!}</td>
                                {{--<td>--}}
                                    {{--{!! Form::hidden('pack_qty[]',$sale->item->pack_qty,['id'=>'pack_qty_'.$key,'class'=>'form-control', 'readonly']); !!}--}}
                                    {{--{!! Form::number('carton[]',$sale->carton,['id'=>'carton_'.$key,'class'=>'form-control', 'readonly']); !!}--}}
                                {{--</td>--}}
                                {{--<td>--}}
                                    {{--{!! Form::number('free_qty[]',$sale->free,['id'=>'free_qty_'.$key,'class'=>'form-control', 'readonly']); !!}--}}
                                    {{--{!! Form::hidden('free[]',$sale->item->free_qty,['id'=>'free_'.$key,'class'=>'form-control', 'readonly']); !!}--}}
                                {{--</td>--}}
                                <td>{!! Form::number('price[]',$sale->price,['id'=>'price_'.$key,'class'=>'form-control price',  'step'=>"any", 'required', 'readonly']); !!}</td>
                                <td>{!! Form::number('total_price[]',$sale->total_price,['id'=>'total_price_'.$key,'class'=>'form-control total_price', 'step'=>"any"]); !!}</td>
                                <td>
                                    @if($loop->last)
                                        <a class="btn btn-success add-row pull-right margin-top-3" href="#" ><i class="fa fa-plus-circle"></i> </a>
                                    @else
                                        <a href="#" class="btn-text-info margin-top-3 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-3 delete-field"><i class="fa fa-close"></i></a>
                                    @endif
                                </td>
                            </tr>

                            @php
                                $sub_total += $sale->total_price;
                            @endphp
                        @endforeach
                        </tbody>
                    </table>

                </div>

            </div>

            <div style="margin-top: 20px; " class="row">
                <div  style="margin-bottom: 10px" class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right amount-info">
                    <table style="width: 100%" class="sales_table_2">
                        <tr>
                            <td  class="total-line">Sub Total</td>
                            <td  class="total-value text-right">
                                {!! Form::number('sub_total',$sub_total,['id'=>'sub_total','class'=>'form-control input-number', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Transport Cost</td>
                            <td  class="total-value text-right">
                                {!! Form::number('transport_cost',0,['id'=>'transport_cost','class'=>'form-control input-number',  'step'=>"any"]); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Labor Cost</td>
                            <td  class="total-value text-right">
                                {!! Form::number('labor_cost',0,['id'=>'labor_cost','class'=>'form-control input-number',  'step'=>"any"]); !!}
                            </td>
                        </tr>
                        <tr>
                            <td class="total-line">Discount Type</td>
                            <td  class="total-value text-right">
                                {!! Form::select('discount_type', ['fixed'=>'Fixed', 'percentage'=>'Percentage'], null,['id'=>'discount_type','class'=>'form-control']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td class="total-line">Discount</td>
                            <td  class="total-value text-right">
                                {!! Form::number('discount',0,['id'=>'discount', 'step'=>"any", 'class'=>'form-control input-number']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Total Amount</td>
                            <td  class="total-value text-right">
                                {!! Form::number('total_amount',$sub_total,['id'=>'total_amount','class'=>'form-control input-number', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Amount to Pay</td>
                            <td  class="total-value text-right">
                                {!! Form::number('amount_to_pay',$sub_total,['id'=>'amount_to_pay','class'=>'form-control input-number', 'readonly']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Paid Amount</td>
                            <td  class="total-value text-right">
                                {!! Form::number('paid_amount',null,['id'=>'paid_amount','class'=>'form-control input-number', 'step'=>"any", 'required']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Due Amount</td>
                            <td  class="total-value text-right">

                                {!! Form::number('due',0,['id'=>'due_amount','class'=>'form-control input-number',  'step'=>"any", 'readonly' ]); !!}
                            </td>
                        </tr>

                    </table>

                </div>
                <div  class="col-lg-6 col-md-6 col-sm-12 col-sx-12 float-right payment-info">
                    <table style="width: 100%" class="sales_table_2">

                        <tr>
                            <td  class="total-line ">Account Name </td>
                            <td  class="total-value">
                                {!! Form::select('cash_or_bank_id', $banks, null,['class'=>'form-control select2 ','required']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td   class="total-line ">Memo No </td>
                            <td  class="total-value">
                                {!! Form::text('memo_no',null,['id'=>'memo_no','class'=>'form-control']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line ">Chalan  No </td>
                            <td  class="total-value">
                                {!! Form::text('chalan_no',null,['id'=>'chalan_no','class'=>'form-control']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line ">Vehicle Number </td>
                            <td  class="total-value">
                                {!! Form::text('vehicle_number',null,['id'=>'vehicle_number','class'=>'form-control']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td   class="total-line ">Payment Method </td>
                            <td  class="total-value">
                                {!! Form::select('payment_method_id', $payment_methods, null,['class'=>'form-control select2 ','required']); !!}
                            </td>
                        </tr>
                        <tr>

                            <td class="total-line ">Delivery System </td>
                            <td  class="total-value">
                                {!! Form::select('delivery_type_id', $delivery_types, null,['class'=>'form-control select2 ','required']); !!}
                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line " colspan="2">
                                <label> Shopping Bags</label><br/>
                                @foreach($bags as $value)
                                    <div class="col-md-3" style="padding-left: 5px; padding-right: 5px;"><label> {{ $value->item_name }}</label>
                                        @php
                                            $sale_track = $model->shopping_bags->pluck('bag_id')->toArray();
                                            $sale_bag = $model->shopping_bags->pluck('qty','bag_id')->toArray();
//                                       dd($sale_track);
                                        @endphp


                                        {{--@foreach($model->shopping_bags as $bag)--}}
                                        {{--@if($bag->bag_id == $value->item_id)--}}
                                        @if(in_array($value->item_id, $sale_track))
                                            {!! Form::number("shopping_bags_".$value->item_id, $sale_bag[$value->item_id],['class'=>'form-control']); !!}
                                        @else
                                            {!! Form::number("shopping_bags_".$value->item_id, null,['class'=>'form-control']); !!}
                                        @endif
                                        {{--@endforeach--}}
                                    </div>
                                @endforeach

                            </td>
                        </tr>
                        <tr>
                            <td  class="total-line">Notation </td>
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
                                <button style="width: 100px" type="submit" class="btn btn-block btn-primary">Save</button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>


            {!! Form::close() !!}


        </div>
    </div>



    @include('member.sales._model_add_customer')

    @push('scripts')
        <script type="text/javascript">

            var editItem = '';
        </script>


        <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
        <!-- CK Editor -->
        <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

        <!-- Date range picker -->
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
                // var today = moment().format('MM\DD\YYYY');
                // $('#date').datepicker('setDate', today);
                // $('#date').datepicker('update');
                // $('.date').datepicker('setDate', today);

                $('.select2').select2();


                var products = [];
                products = <?php print_r(json_encode($products)); ?>;

                    {{--var $customers = [];--}}

                    {{--var optionName;--}}
                    {{--function customer() {--}}

                    {{--optionName='<option selected="selected" value="">Select Customer Name</option>';--}}

                    {{--$.each( $customers, function( key, value ) {--}}
                    {{--optionName = optionName+'<option value="'+key+'" >'+value+'</option>';--}}
                    {{--});--}}

                    {{--return optionName;--}}
                    {{--}--}}


                var i = 1;
                $(document).on('click', '.add-row', function () {
                    var optionName = '';
                    var $div = $(this).parent().parent();
                    var childrenTd = $div.children();
                    if($div.find('td:first-child').find('select').val() == ""  || $div.find('td:nth-child(6)').find('input').val() == "")
                    {
                        bootbox.alert("Please Select Item Name or input value  Qty/Price");
                        return false;
                    }

                    var itemId = $div.find('td:first-child').find('select').val();
                    delete products[itemId];

                    optionName='<option selected="selected" value="">Select Item Name</option>';
                    $.each( products, function( key, value ) {
                        optionName = optionName+'<option value="'+key+'" >'+value+'</option>';
                    });

                    var html = '<tr class="item-row">' +
                        '<td class="item-name">' +
                        editItem +
                        '<select id="product_id_'+i+'" data-option="'+i+'" class="form-control select2 item-name" name="product_id[]" >'+optionName+'</select>' +
                        '</td>' +
                        '<td class="description">' +
                        '{!! Form::text('description[]', null,['class'=>'form-control' ]); !!}' +
                        '</td>' +
                        '<td><input id="stock_'+i+'" class="form-control"  name="available_stock[]" readonly="" type="text"></td>' +
                        '<td>' +
                        '<input id="unit_'+i+'" class="form-control" name="unit[]" readonly="" type="text">' +
                        '</td>' +
                        '<td><input id="qty_'+i+'" class="form-control input-number qty" step="any" name="qty[]" type="number" min="0.00"></td>' +
                        // '<td><input id="pack_qty_'+i+'" class="form-control" name="pack_qty[]" readonly="" type="hidden" min="0"><input id="carton_'+i+'" class="form-control" name="carton[]" readonly="" type="number" min="0"></td>' +
                        // '<td><input id="free_qty_'+i+'" class="form-control" name="free_qty[]" readonly="" type="hidden" min="0"><input id="free_'+i+'" class="form-control" name="free[]" readonly="" type="number" min="0"></td>' +
                        '<td><input id="price_'+i+'" class="form-control  input-number price" step="any" name="price[]" type="number" min="0.00"></td>' +
                        '<td><input id="total_price_'+i+'" class="form-control  input-number total_price" step="any" name="total_price[]" type="number" min="0.00" required></td>' +
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
                                    label_text = data.item_name+" (Out of Stock)";
                                    $('#bags_'+productId).text(label_text);
                                }

                            }else{
                                bootbox.alert("No data Found!! ");
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
                    $mainDiv.last().find('td:nth-child(9) input').attr('readonly', true);
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
                    var target = $(this).data('target');
                    var qty = $(this).val();
                    var stock = $div.find('td:nth-child(3)').find('input').val();

                    var free_qty = $('#free_'+target).val();
                    var pack_qty = $('#pack_qty_'+target).val();

                    $('#free_qty_'+target).val(parseInt(qty/free_qty));
                    $('#carton_'+target).val(parseInt(qty/pack_qty));


                    if(parseFloat(stock)<parseFloat($(this).val()))
                    {
                        $(this).val('');
                        bootbox.alert("Sales quantity can't cross available stock");
                        return false;
                    }
                });

                $(document).on('keyup','#transport_cost, #labor_cost, #discount, #paid_amount', function(e) {
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

                        var price = $tr.find('tr:nth-child('+i+') td:nth-child(6) input').val();
                        // var total_price = $tr.find('tr:nth-child('+i+') td:nth-child(7) input').val();
                        qty =  qty == undefined || qty == "" ? 0 : parseFloat(qty);
                        price =  price == undefined || price == "" ? 0 : parseFloat(price);
                        // total_price =  total_price == undefined || total_price == "" ? 0 : parseFloat(total_price);

                        var total_price = qty*price;
                        $tr.find('tr:nth-child('+i+') td:nth-child(7) input').val(total_price.toFixed(2));

                        total_bill = (total_bill+total_price);
                    }
                    total_bill = total_bill.toFixed(2);
                    $('#sub_total').val(total_bill);
                }

                function total_price_calculate()
                {
                    price_calculate();

                    var total_bill = $('#sub_total').val();
                    // var shipping_charge = parseFloat($('#shipping_charge').val());
                    var transport_cost = parseFloat($('#transport_cost').val());
                    var labor_cost = parseFloat($('#labor_cost').val());
                    var discount_type = $('#discount_type').val();
                    var discount = $('#discount').val();
                    var discount_amount = 0;
                    discount = parseFloat(discount);

                    var paid_amount = $('#paid_amount').val();

                    paid_amount = paid_amount == '' ? 0 : paid_amount;

                    total_bill = parseFloat(total_bill);
                    paid_amount = parseFloat(paid_amount);
                    discount_amount = parseFloat(discount_amount);

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
                            var html = "<label> Customer Name:</label> "+data.customer.name+"<br/>"+"<label> Address: </label> "+data.customer.address+"<br/>"+"<label> Phone: </label> "+data.customer.phone+"<br/>"
                            $('#customer_details').html(html);
                        }else{
//                        console.log(data);
                            bootbox.alert("No data Found!! ");
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
                                        bootbox.alert(data.item_name+" already available in sells Or Not available");
                                    }

                                }else{
                                    bootbox.alert(data.item_name+" Out Of stock ");
                                }


                            }else{
                                bootbox.alert("No data Found!! ");
                            }
                        });
                }


                $("#paid_amount").keyup( function (e) {
                    e.preventDefault();

                    var price = parseFloat($(this).val());
                    var total_amount = parseFloat($("#amount_to_pay").val());

                    if(price>total_amount){
                        bootbox.alert("Paid amount can't be bigger than Amount to Pay");
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
                            bootbox.alert("Unable to Save");
                        }
                        console.log(data);

                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        var error = $.parseJSON(jqXHR.responseText);
                        // console.log(error);
                        bootbox.alert("Name/phone is required & Unique and Address Required");
                        // alert(textStatus);
                        // alert(errorThrown);
                    });

                    e.preventDefault();
                });
            });
        </script>


    @endpush


@endsection

