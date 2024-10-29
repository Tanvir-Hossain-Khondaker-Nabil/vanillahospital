<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/27/2019
 * Time: 3:54 PM
 */

 $route =  \Auth::user()->can(['member.requisition.index']) ? route('member.requisition.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Requisition Purchase',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => ' Requisition Purchase',
    'title'=> ' Requisition Purchase',
    'heading' => ' Requisition Purchase',
];

?>
@extends('layouts.back-end.master', $data)


@push('styles')

    <style type="text/css">
        .content{
            font-size: 11px;
        }
        textarea.form-control{
            height: 103px;
        }
        .head-trans div{
            padding-left: 5px !important;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/plugins/iCheck/all.css')}}">
@endpush


@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

        @include('common._alert')

        @include('common._error')
        <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">  Requisition Purchase</h3>
                </div>


                {!! Form::open(['route' => 'member.purchases_by_requisition.store','method' => 'POST', 'files'=>true, 'id'=>'purchase_form', 'role'=>'form' ]) !!}
                {!! Form::hidden('is_requisition', $is_requisition, [ 'class'=>'form-control','required']); !!}
                {!! Form::hidden('requisition_id', $modal->id, [ 'class'=>'form-control','required']); !!}
                <div class="box-body">

                    
<div class="box">
    <div class="box-body">
        <div class="col-md-2 pl-1">
            <label for="suppliers"> Date </label>
            {!! Form::text('date', null,['id'=>'date', 'class'=>'form-control','required', 'autocomplete'=>"off"]); !!}
        </div>
        <div class="col-md-3">
            <label for="supplier_id"> Supplier Name </label>
            {!! Form::select('supplier_id', $suppliers, null,['id'=>'supplier_id', 'class'=>'form-control select2','required', 'placeholder'=>'Select Supplier Name']); !!}
            <div id="current-balance">  </div>
        </div>
        <div class="col-md-2">
            <label for="memo_no"> Memo / Chalan No.</label>
            {!! Form::text('memo_no', $memo_no, [ 'class'=>'form-control','required', 'readonly']); !!}
        </div>

        <div class="col-md-2">
            <label for="invoice_no"> Invoice No. </label>
            {!! Form::text('invoice_no', null, [ 'class'=>'form-control']); !!}
        </div>
        <div class="col-md-3">
            <label for="avg_purchase_price"> Avg. Purchase Price </label>
            {!! Form::text('avg_purchase_price', null, ['id'=>'avg_purchase_qty', 'class'=>'form-control', 'readonly']); !!}
        </div>
    </div>
</div>

<div class="box">
    
    @foreach( $modal->requisition_details as $key => $value)

    <div class="box-body requisition-purchase-box purchase-box-req">

        <div class="col-md-4">
            @if($key==0) <label for="product_id">Item Name </label> @endif
            {!! Form::hidden('requisition_details_id', $value->id, [ 'class'=>'form-control','required']); !!}
            {!! Form::hidden('product_id[]', $value->item_id, [ 'class'=>'form-control','required']); !!}
            {!! Form::input('product_name[]', $products, $value->item->item_name,['id'=>'product_id_'.$key, 'data-option'=>$key, 'class'=>'form-control item-name','required', 'placeholder'=>'Select Item Name', 'readonly']); !!}
        </div>

        <div class="col-md-1">
            @if($key==0) <label for="amount"> Unit </label> @endif
            {!! Form::text('unit[]',$value->unit,['id'=>'unit_'.$key,'class'=>'form-control', 'disabled']); !!}
        </div>
        <div class="col-md-1">
            @if($key==0) <label for="amount"> Available Stock </label> @endif
            {!! Form::number('available_stock[]',$value->item->stock_details->stock,['id'=>'stock_'.$key,'class'=>'form-control', 'readonly']); !!}
        </div>
{{--        <div class="col-md-1">--}}
{{--            <label for="amount"> Sales Price </label>--}}
{{--            {!! Form::number('sales_price[]',null,['id'=>'sales_price_0','class'=>'form-control', 'readonly']); !!}--}}
{{--        </div>--}}
        <div class="col-md-1">
            @if($key==0) <label for="amount"> Qty </label> @endif
            {!! Form::number('qty[]',$value->qty,['id'=>'qty_'.$key,'class'=>'form-control qty', 'required', 'step'=>"any"]); !!}
        </div>
        <div class="col-md-2">
            @if($key==0) <label for="amount"> Price </label> @endif
            {!! Form::number('price[]',$value->price,['id'=>'price_'.$key,'class'=>'form-control input-number price','placeholder'=>' Price', 'step'=>"any", 'required']); !!}
        </div>
        <div class="col-md-2">
            @if($key==0) <label for="amount"> Total Price </label> @endif
            {!! Form::number('total_price[]',$value->qty*$value->price,['id'=>'total_price_'.$key,'class'=>'form-control input-number total_price','placeholder'=>'Total Price', 'step'=>"any", 'required', 'readonly']); !!}
        </div>
        <div class="col-md-1">
            @if($key==0) <label for="description">Description  </label> @endif
            {!! Form::text('description[]',$value->description,['id'=>'description','class'=>'form-control' ,'placeholder'=>'Enter Description','height'=>'70px !important' ]); !!}
        </div>
        <div class="col-md-1" style=" padding-top:{{ $loop->last ?  0 : ($key==0?30:10) }}px">
            @if($loop->last)
            {{--<a class="btn btn-success add-row pull-right margin-top-3" href="#" ><i class="fa fa-plus-circle"></i> </a>--}}
            @else
                <a href="#" class="btn-text-info margin-top-3  margin-top-23 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a>
            @endif
        </div>
    </div>

    
    @endforeach
</div>
<div class="col-md-12">
    <div class="">
        <div class="col-md-4">
            <div class="form-group">
                <label for="notation">Notation  </label>
                {!! Form::textarea('notation',$modal->notation,['id'=>'notation','class'=>'form-control' ,'placeholder'=>'Enter Notation','height'=>'70px !important' ]); !!}
            </div>
            <div class="form-group">
                <label for="product_id">Cash or Bank Account  </label>
                {!! Form::select('cash_or_bank_id', $banks, config('settings.cash_bank_id'),['class'=>'form-control select2 ','required', 'placeholder'=>'Select Account Name']); !!}
            </div>
            <div class="form-group">
                <label for="payment_method_id"> Payment Method  </label>
                {!! Form::select('payment_method_id', $payment_methods, null,['class'=>'form-control select2 ','required']); !!}
            </div>
            <div class="form-group">
                <label for="amount">Transport Vehicle Number </label>
                {!! Form::text('vehicle_number',null,['id'=>'vehicle_number','class'=>'form-control ','placeholder'=>'Enter Transport Vehicle Number']); !!}
            </div>
            <div class="form-group">
                <label for=file> Attach if File Available  </label>
                <input  name="file" type="file" accept="image/*"/>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label> Payment Option</label> <br/>
                <label>
                    <input type="checkbox" id="payment_option"  name="payment_option">
                    Due Credit
                </label>
            </div>
            <div class="form-group">
                <label for="amount"> Total Bill </label>
                {!! Form::number('total_bill',$modal->total_price,['id'=>'total_bill','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"any", 'required', 'readonly']); !!}
            </div>
            <div class="form-group">
                <label for="due_advance" id="due_advance"> Due/Advance </label>
                {!! Form::number('due',0,['id'=>'total_due_adv','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"any", 'required', 'readonly']); !!}
            </div>
            <div class="form-group">
                <label for="amount"> Discount Type</label>
                {!! Form::select('discount_type', ['Fixed'=>'Fixed', 'Percentage'=>'Percentage'], null,['id'=>'discount_type','class'=>'form-control']); !!}
            </div>
            <div class="form-group">
                <label for="amount"> Discount </label>
                {!! Form::number('discount',0,['id'=>'discount','class'=>'form-control input-number']); !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="transport_cost"> Transport Cost </label>
                {!! Form::number('transport_cost', 0,['id'=>'transport_cost','class'=>'form-control input-number','placeholder'=>'Enter Amount', 'step'=>"any", 'required']); !!}
            </div>
            <div class="form-group">
                <label for="unload_cost"> Unload Cost</label>
                {!! Form::number('unload_cost', 0, ['id'=>'unload_cost','class'=>'form-control input-number','placeholder'=>'Enter Amount', 'step'=>"any", 'required']); !!}
            </div>
            <div class="form-group">
                <label for="unload_cost"> Bank Charge</label>
                {!! Form::number('bank_charge', 0, ['id'=>'bank_charge','class'=>'form-control input-number','placeholder'=>'Enter Amount', 'step'=>"any", 'required']); !!}
            </div>
            <div class="form-group">
                <label for="amount"> Total Amount</label>
                {!! Form::number('total_amount',$modal->total_price,['id'=>'total_amount','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"any", 'required', 'readonly']); !!}
            </div>
            <div class="form-group">
                <label for="amount"> Amount to Pay </label>
                {!! Form::number('amount_to_pay',0,['id'=>'amount_to_pay','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"any", 'required', 'readonly']); !!}
            </div>
            <div class="form-group">
                <label for="amount"> Paid Price</label>
                {!! Form::number('paid_amount',0,['id'=>'paid_amount','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"any", 'required']); !!}
            </div>

        </div>
    </div>
</div>



                    <div class="box-footer">
                        <div class="col-md-12 text-right">
                            <button type="submit" id="submit" class="btn btn-primary">Save</button>
                        </div>
                    </div>

                </div>
                <!-- /.box-body -->

            {!! Form::close() !!}
            <!-- /.box -->
            </div>
        </div>
    </div>

@endsection


@push('scripts')

    <!-- Date range picker -->
    <script src="{{ asset('public/adminLTE/bower_components/moment/min/moment.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- CK Editor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

    <!-- Date range picker -->
    <script type="text/javascript">

        // var date = new Date();
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

            $('.select2').select2();

            var products = [];
            products = <?php print_r(json_encode($purchase_products)); ?>;


            var i = {{ count($modal->requisition_details) }};

            $(document).on('click', '.add-row', function () {
                var optionName = '';
                var $div = $(this).parent().parent('.box-body').parent('.box');
                var childrenDiv = $div.children();

                if($div.find('div :first-child').find('select').val() == "" || $div.find('div :nth-child(5)').find('input').val() == "" || $div.find('div :nth-child(6)').find('input').val() == "")
                {
                    bootbox.alert("Please Select Item Name or input value  Qty/Price");
                    return false;
                }

                var itemId = $div.find('div :first-child').find('select').val();
                delete products[itemId];

                optionName='<option selected="selected" value="">Select Item Name</option>';
                $.each( products, function( key, value ) {
                    optionName = optionName+'<option value="'+key+'" >'+value+'</option>';
                });

                var html = '<div class="box-body purchase-box purchase-box-req ">' +
                    '<div class="col-md-3">' +
                    '<input type="hidden" name="purchaseDetails[]" value="new">' +
                    '<select id="product_id_'+i+'" data-option="'+i+'" class="form-control select2 item-name"  name="product_id[]">'+optionName+'</select>' +
                    '</div> '+
                    '<div class="col-md-1">' +
                    '<input id="unit_'+i+'" readonly  min="0" class="form-control" name="unit[]" type="text">'+
                    '</div>' +
                    '<div class="col-md-1">' +
                    '<input id="last_purchase_qty_'+i+'" readonly class="form-control" name="last_purchase_qty[]" type="number">'+
                    '</div>'  +
                    '<div class="col-md-1">' +
                    '<input id="stock_'+i+'" readonly class="form-control" name="available_stock[]" type="number">'+
                    '</div>' +
                    // '<div class="col-md-1">' +
                    // '<input id="sales_price_'+i+'" readonly class="form-control" name="sales_price[]" type="number">'+
                    // '</div>' +
                    '<div class="col-md-1">'+
                    '<input id="qty_'+i+'" class="form-control qty" step="any" min="0" name="qty[]" type="number">'+
                    '</div>'+
                    '<div class="col-md-1">' +
                    '<input placeholder=" Price"  min="0"  id="price_' + i + '" data-option="' + i + '" class="form-control price input-number" step="any" name="price[]"  type="number"/>' +
                    '</div>' +
                    '<div class="col-md-1">' +
                    '<input placeholder="Total Price" min="0"  id="price_' + i + '" data-option="' + i + '" class="form-control input-number" step="any" name="total_price[]"  type="number"/>' +
                    '</div>' +
                    '<div class="col-md-1">' +
                    '{!! Form::text('description[]',null,['id'=>'description','class'=>'form-control' ,'placeholder'=>'Enter Description','height'=>'70px !important' ]); !!}' +
                    '</div>' +
                    '<div class="col-md-1"> <a class="btn btn-success add-row pull-right margin-top-3" href="#" ><i class="fa fa-plus-circle"></i> </a>' +
                    '</div>'+
                    '</div>';

                i++;

                $(this).parent().css("padding-top", '10px');
                // console.log(childrenDiv);
                childrenDiv.find('input').attr('readonly', true);
                childrenDiv.find('input').attr('required', true);
                childrenDiv.find('div:nth-child(8)').find('input').attr('required', false);
                childrenDiv.find('select').attr('readonly', true);
                childrenDiv.find('select').attr('required', true);
                var actionHtml = '<a href="#" class="btn-text-info margin-top-3 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a></span>';
                $(this).parent('div').html(actionHtml);

                $('input[type=number]').attr('min', 0);
                $div.append(html);
                $('.select2').select2();

            });

            $(document).on('click','.edit-button', function() {
                var $mainDiv = $(this).parent().parent().parent('.box').children();
                $mainDiv.find('input').attr('readonly', true);
                $mainDiv.find('select').attr('readonly', true);

                var $div = $(this).parent().parent('.box-body');
                var childrenDiv = $div.children();
                childrenDiv.find('input').attr('readonly', false);
                childrenDiv.find('select').attr('readonly', false);
                $(this).parent().css("padding-top", '0px');
                var actionHtml = '<a class="btn btn-warning pull-right margin-top-3" id="edit-complete" href="#" ><i class="fa fa-check-circle"></i> </a>';
                $(this).parent('div').html(actionHtml);
            });

            $(document).on('click','.delete-field', function(e) {
                e.preventDefault();

                var $div = $(this).parent().parent('.box-body');
                $div.remove();

                total_price_calculate();
            });

            $(document).on('click','#edit-complete', function() {

                var $div = $(this).parent().parent('.box-body');
                var childrenDiv = $div.children();
                $(this).parent().css("padding-top", '10px');
                childrenDiv.find('input').attr('readonly', true);
                childrenDiv.find('select').attr('readonly', true);
                var $mainDiv = $(this).parent().parent().parent('.box').children();
                $mainDiv.last().find('input').attr('readonly', false);
                $mainDiv.last().find('select').attr('readonly', false);
                var actionHtml = '<a href="#" class=" btn-text-info margin-top-3 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a>';
                $(this).parent('div').html(actionHtml);

                var $lastDiv = $('.main-box').children('.box-body').last();

                // console.log($mainDiv);
                $lastDiv.find('input').attr('readonly', false);
                $lastDiv.find('select').attr('readonly', false);
            });

            var target = '';

            $(document).on('keyup','.qty, .price', function(e) {
                e.preventDefault();

                total_price_calculate();
            });

            $(document).on('keyup','#transport_cost, #unload_cost, #discount, #paid_amount', function(e) {
                e.preventDefault();

                total_price_calculate();
            });

            $(document).on('change','#discount_type', function(e) {
                e.preventDefault();

                total_price_calculate();
            });

            function price_calculate(){
                var total_bill = 0;
                for(var i = 1; i<=$('.purchase-box-req').length; i++) {

                    var qty = $('.purchase-box-req:nth-child(' + i + ') div:nth-child(4) input').val();
                    // var total_price = $('.purchase-box:nth-child(' + i + ') div:nth-child(8) input').val();

                    // alert(qty);
                    var price = $('.purchase-box-req:nth-child(' + i + ') div:nth-child(5) input').val();
                    price =  price == undefined || price == "" ? 0 : parseFloat(price);
                    var total_price = parseFloat(qty*price);

                    // qty =  qty == undefined || qty == "" ? 0 : parseInt(qty);
                    // total_price =  total_price == undefined || total_price == "" ? 0 : parseFloat(total_price);
                    // var price = parseFloat(total_price/qty).toFixed(2);

                    total_bill = total_bill+total_price;
                    // $('.purchase-box:nth-child(' + i + ') div:nth-child(7) input').val(price);
                    $('.purchase-box-req:nth-child(' + i + ') div:nth-child(6) input').val(total_price);
                }
                $('#total_bill').val(total_bill);
            }


            function total_price_calculate()
            {

                price_calculate();

                var total_bill = $('#total_bill').val();
                var total_amount = $('#total_amount').val();
                var unload_cost = $('#unload_cost').val();
                var transport_cost =$('#transport_cost').val();
                var discount_type = $('#discount_type').val();
                var discount = $('#discount').val();
                var discount_amount = 0;

                var paid_amount = $('#paid_amount').val();

                unload_cost = unload_cost == '' ? 0 : unload_cost;
                transport_cost = transport_cost == '' ? 0 : transport_cost;
                paid_amount = paid_amount == '' ? 0 : paid_amount;
                discount = discount == '' ? 0 : discount;

                total_bill = parseFloat(total_bill);
                unload_cost = parseFloat(unload_cost);
                transport_cost = parseFloat(transport_cost);
                paid_amount = parseFloat(paid_amount);
                discount = parseFloat(discount);
                discount_amount = parseFloat(discount_amount);

                var total_amount = total_bill+unload_cost+transport_cost;

                if(discount_type=="Fixed")
                    discount_amount = discount;
                else
                    discount_amount = total_bill*discount/100;

                var amount_to_pay = total_amount-discount_amount;
                var last_amount = amount_to_pay-paid_amount;

                $('#total_amount').val(total_amount);
                $('#amount_to_pay').val(amount_to_pay);

                $('#total_due_adv').val(last_amount);
                if(last_amount<0)
                {
                    $('#due_advance').text('Advance');
                    $('#due_advance').css('color', 'green');
                }else{
                    $('#due_advance').text('Due');
                    $('#due_advance').css('color', 'red');
                }
            }

            $(document).on('click','.btn-at', function() {
                var $el = $(this);
                target = $el.data('option');
            });

            var url = "{{ route('search.item_details') }}";
            $(document).on('change', '.item-name', function(e){
                e.preventDefault();

                var itemId = $(this).val();

                var supplier_id = $('#supplier_id').val();
                var id = $(this).data('option');

                if( supplier_id == '') {
                    $(this).val('');
                    $('.select2').select2();
                    bootbox.alert("Please Select Supplier Name");
                    return false;
                }

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'item_id' : itemId,
                    'supplier_id' : supplier_id
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

                        if(data.status == "success")
                        {
                            console.log(data);
                            $('#sales_price_'+id).val(data.price);
                            $('#stock_'+id).val(data.stock);
                            $('#unit_'+id).val(data.unit);
                            $('#last_purchase_qty_'+id).val(data.supplier_purchases ? data.supplier_purchases.qty : 0);
                        }else{
//                        console.log(data);
                            bootbox.alert("No data Found!! ");
                        }
                    });
            });

            $("#supplier_id").change( function(e){
                e.preventDefault();
                var url = "{{ route('search.supplier_info') }}";
                var supplier_id = $(this).val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'supplier_id' : supplier_id
                };

                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function(data) {

                    if(data.status == "success")
                    {

                        var html = "<b> Current Balance: "+data.supplier.supplier_current_balance+"</b><br/>";
                        html += "<b> Last Purchase Amount: "+( data.last_purchase_amount==null ? 0 : data.last_purchase_amount.total_amount )+"</b>";
                        $('#current-balance').html(html);
                    }else{
//                        console.log(data);
                        bootbox.alert("No data Found!! ");
                    }
                });


            });
        });

    </script>

@endpush
