<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/14/2019
 * Time: 3:04 PM
 */

?>


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

@endpush
<div class="box">
    <div class="box-body">
        <div class="col-md-2  px-1">
            <label for="suppliers"> Date </label>
            {!! Form::text('date', create_date_format($modal->date,'/'),['id'=>'date', 'class'=>'form-control','required']); !!}
        </div>
        <div class="col-md-4  px-1">
            <label for="supplier_id"> Supplier Name </label>
            {!! Form::select('supplier_id', $suppliers, null,['id'=>'supplier_id', 'class'=>'form-control select2','required', 'placeholder'=>'Select Supplier Name']); !!}
            <div id="current-balance">  </div>
        </div>
        <div class="col-md-2  px-1">
            <label for="memo_no"> Memo No. </label>
            {!! Form::text('memo_no', $memo_no, [ 'class'=>'form-control','required', 'readonly']); !!}
        </div>
        <div class="col-md-2  px-1">
            <label for="chalan_no"> Chalan No. </label>
            {!! Form::text('chalan', $chalan_no, ['class'=>'form-control','required', 'readonly']); !!}
        </div>
        <div class="col-md-2  px-1">
            <label for="invoice_no"> Invoice No. </label>
            {!! Form::text('invoice_no', null, [ 'class'=>'form-control']); !!}
        </div>
    </div>
</div>

<div class="box">

    @foreach( $modal->purchase_details as $key => $value)
    <div class="box-body purchase-box">
        <div class="col-md-3">
            <input type="hidden" name="purchaseDetails[]" value="{{ $value->id }}">
            @if($key==0) <label for="product_id">Item Name </label> @endif
            {!! Form::select('product_id[]', $products, $value->item_id,['id'=>'product_id_'.$key, 'data-option'=>$key, 'class'=>'form-control select2 item-name','required', 'placeholder'=>'Select Item Name', !$loop->last ? "readonly":'']); !!}
        </div>

        <div class="col-md-1">
            @if($key==0) <label for="amount"> Unit </label> @endif
            {!! Form::text('unit[]',$value->unit,['id'=>'unit_'.$key,'class'=>'form-control', 'readonly']); !!}
        </div>
        <div class="col-md-1">
            @if($key==0) <label for="amount"> Last Purchase Qty </label> @endif
            {!! Form::number('last_purchase_qty[]',$value->last_purchase_qty,['id'=>'last_purchase_qty_'.$key,'class'=>'form-control', 'readonly']); !!}
        </div>
        <div class="col-md-1">
            @if($key==0) <label for="amount"> Available Stock </label> @endif
            {!! Form::number('available_stock[]',$value->available_stock,['id'=>'stock_'.$key,'class'=>'form-control', 'readonly']); !!}
        </div>
        {{--<div class="col-md-1">--}}
            {{--@if($key==0) <label for="amount"> Sales Price </label> @endif--}}
            {{--{!! Form::number('sales_price[]',$value->sales_price,['id'=>'sales_price_'.$key,'class'=>'form-control', 'readonly']); !!}--}}
        {{--</div>--}}
        <div class="col-md-1">
            @if($key==0) <label for="amount"> Qty </label> @endif
            {!! Form::number('qty[]',$value->qty,['id'=>'qty_'.$key,'class'=>'form-control qty', 'step'=>"any", 'required']); !!}
        </div>
        <div class="col-md-2">
            @if($key==0) <label for="amount"> Price </label> @endif
            {!! Form::number('price[]',$value->price,['id'=>'price_'.$key,'class'=>'form-control input-number price','placeholder'=>' Price', 'step'=>"any", 'required']); !!}
        </div>
        <div class="col-md-2">
            @if($key==0) <label for="amount"> Total Price </label> @endif
            {!! Form::number('total_price[]',$value->qty*$value->price,['id'=>'total_price_'.$key,'class'=>'form-control input-number','placeholder'=>'Total Price', 'step'=>"any", 'required', 'readonly']); !!}
        </div>
        <div class="col-md-1">
            @if($key==0) <label for="description">Description  </label> @endif
            {!! Form::text('description[]',null,['id'=>'description','class'=>'form-control' ,'placeholder'=>'Enter Description','height'=>'70px !important' ]); !!}
        </div>
        <div class="col-md-1" style=" padding-top:{{ $loop->last && $key!=0  ? "0px" : "20px" }}">
            @if($loop->last)
            <a class="btn btn-success add-row pull-right margin-top-3" href="#" ><i class="fa fa-plus-circle"></i> </a>
            @else
                <a href="#" class="btn-text-info margin-top-3  margin-top-23 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a>
            @endif
        </div>
    </div>
    @endforeach
</div>
<div class="col-md-12">
    <div class="">
        <div class="col-md-4  pl-0">
            <div class="form-group">
                <label for="notation">Notation  </label>
                {!! Form::textarea('notation',null,['id'=>'notation','class'=>'form-control' ,'placeholder'=>'Enter Notation','height'=>'70px !important' ]); !!}
            </div>
            <div class="form-group">
                <label for="product_id">Cash or Bank Account  </label>
                {!! Form::select('cash_or_bank_id', $banks, null,['class'=>'form-control select2 ','required', 'placeholder'=>'Select Account Name']); !!}
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
                <input  name="attach" type="file" accept="image/*"/>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="amount"> Total Bill </label>
                {!! Form::number('total_bill',$modal->total_price,['id'=>'total_bill','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"any", 'required', 'readonly']); !!}
            </div>
            <div class="form-group">
                <label for="due_advance" id="due_advance"> {{$modal->due_amount>0? "Due":"Advance" }}</label>
                {!! Form::number('due_amount',$modal->due_amount>0? $modal->due_amount:$modal->advance_amount,['id'=>'total_due_adv','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"any", 'required', 'readonly']); !!}
            </div>
            <div class="form-group">
                <label for="amount"> Discount Type</label>
                {!! Form::select('discount_type', ['Fixed'=>'Fixed', 'Percentage'=>'Percentage'], null,['id'=>'discount_type','class'=>'form-control']); !!}
            </div>
            <div class="form-group">
                <label for="amount"> Discount </label>
                {!! Form::number('discount',$modal->discount,['id'=>'discount','class'=>'form-control input-number']); !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="transport_cost"> Transport Cost </label>
                {!! Form::number('transport_cost', $modal->transport_cost,['id'=>'transport_cost','class'=>'form-control input-number','placeholder'=>'Enter Amount', 'step'=>"any", 'required']); !!}
            </div>
            <div class="form-group">
                <label for="unload_cost"> Unload Cost</label>
                {!! Form::number('unload_cost', $modal->unload_cost, ['id'=>'unload_cost','class'=>'form-control input-number','placeholder'=>'Enter Amount', 'step'=>"any", 'required']); !!}
            </div>
            <div class="form-group">
                <label for="unload_cost"> Bank Charge</label>
                {!! Form::number('bank_charge', 0, ['id'=>'bank_charge','class'=>'form-control input-number','placeholder'=>'Enter Amount', 'step'=>"any", 'required']); !!}
            </div>
            <div class="form-group">
                <label for="amount"> Total Amount</label>
                {!! Form::number('total_amount',$modal->total_amount,['id'=>'total_amount','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"any", 'required', 'readonly']); !!}
            </div>
            <div class="form-group">
                <label for="amount"> Amount to Pay </label>
                {!! Form::number('amount_to_pay',$modal->amt_to_pay,['id'=>'amount_to_pay','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"any", 'required', 'readonly']); !!}
            </div>
            <div class="form-group">
                <label for="amount"> Paid Price</label>
                {!! Form::number('paid_amount',$modal->paid_amount,['id'=>'paid_amount','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"any", 'required']); !!}
            </div>

        </div>
    </div>
</div>

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
            $('.select2').select2();

            var products = [];
            products = <?php print_r(json_encode($purchase_products)); ?>;

            var i = {{ count($modal->purchase_details) }};

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

                var html = '<div class="box-body purchase-box ">' +
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

                $(this).parent().css("padding-top", '30px');
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
                mouseWheelOff();
            });

            $(document).on('click','.edit-button', function() {
                var $mainDiv = $(this).parent().parent().parent('.box').children();
                $mainDiv.find('input').attr('readonly', true);
                $mainDiv.find('select').attr('readonly', true);

                var $div = $(this).parent().parent('.box-body');
                var childrenDiv = $div.children();
                childrenDiv.find('input').attr('readonly', false);
                childrenDiv.find('select').attr('readonly', false);
                $(this).parent().css("padding-top", '20px');
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
                $(this).parent().css("padding-top", '30px');
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

            $(document).on('keyup','#transport_cost, #unload_cost, #discount, #paid_amount, #bank_charge', function(e) {
                e.preventDefault();

                total_price_calculate();
            });

            $(document).on('change','#discount_type', function(e) {
                e.preventDefault();

                total_price_calculate();
            });

            function price_calculate(){
                var total_bill = 0;
                for(var i = 1; i<=$('.purchase-box').length; i++) {
                    var qty = $('.purchase-box:nth-child(' + i + ') div:nth-child(5) input').val();
                    var price = $('.purchase-box:nth-child(' + i + ') div:nth-child(6) input').val();
                    qty =  qty == undefined || qty == "" ? 0 : parseFloat(qty);
                    price =  price == undefined || price == "" ? 0 : parseFloat(price);
                    price =  parseFloat(price);
                    var total_price = parseFloat(qty*price);
                    total_bill = total_bill+total_price;
                    $('.purchase-box:nth-child(' + i + ') div:nth-child(7) input').val(total_price);
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
                var bank_charge = $('#bank_charge').val();
                var discount_type = $('#discount_type').val();
                var discount = $('#discount').val();
                var discount_amount = 0;
                discount = parseFloat(discount);



                var paid_amount = $('#paid_amount').val();

                unload_cost = unload_cost == '' ? 0 : unload_cost;
                transport_cost = transport_cost == '' ? 0 : transport_cost;
                paid_amount = paid_amount == '' ? 0 : paid_amount;
                bank_charge = bank_charge == '' ? 0 : bank_charge;

                total_bill = parseFloat(total_bill);
                unload_cost = parseFloat(unload_cost);
                transport_cost = parseFloat(transport_cost);
                paid_amount = parseFloat(paid_amount);
                bank_charge = parseFloat(bank_charge);
                discount_amount = parseFloat(discount_amount);

                var total_amount = total_bill+unload_cost+transport_cost+bank_charge;

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
