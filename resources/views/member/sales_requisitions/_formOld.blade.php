<?php
/**
 * Created by PhpStorm.
 * User: Mobarok-RC
 * Date: 9/9/2021
 * Time: 12:49 PM
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
    <link rel="stylesheet" href="{{ asset('public/adminLTE/plugins/iCheck/all.css')}}">
@endpush

<div class="box">
    <div class="box-body">
        <div class="col-md-2">
            <label for="suppliers"> Date </label>
            {!! Form::text('date', null,['id'=>'date', 'class'=>'form-control','required']); !!}
        </div>
    </div>
</div>


<div class="box">
    <div class="box-body requisition-purchase-box">

        <div class="col-md-3">
            <label for="product_id">Item Name </label>
            {!! Form::select('product_id[]', $products, null,['id'=>'product_id_0', 'data-option'=>'0', 'class'=>'form-control select2 item-name','required', 'placeholder'=>'Select Item Name']); !!}
        </div>

        <div class="col-md-1">
            <label for="amount"> Unit </label>
            {!! Form::text('unit[]',null,['id'=>'unit_0','class'=>'form-control', 'disabled']); !!}
        </div>
        {{--<div class="col-md-1">--}}
        {{--<label for="amount"> Last Purchase Qty </label>--}}
        {{--{!! Form::number('last_purchase_qty[]',null,['id'=>'last_purchase_qty_0','class'=>'form-control', 'readonly']); !!}--}}
        {{--</div>--}}
        {{--<div class="col-md-1">--}}
        {{--<label for="amount"> Last Purchase Price </label>--}}
        {{--{!! Form::number('last_purchase_price[]',null,['id'=>'last_purchase_price_0','class'=>'form-control', 'readonly']); !!}--}}
        {{--</div>--}}
        <div class="col-md-1">
            <label for="amount"> Available Stock </label>
            {!! Form::number('available_stock[]',null,['id'=>'stock_0','class'=>'form-control', 'readonly']); !!}
        </div>
        <div class="col-md-1">
            <label for="amount"> Qty </label>
            {!! Form::number('qty[]',null,['id'=>'qty_0','class'=>'form-control qty', 'required', 'step'=>"any"]); !!}
        </div>
        <div class="col-md-1">
            {!! Form::hidden('pack_qty[]',null,['id'=>'pack_qty_0','class'=>'form-control', 'readonly']); !!}
            {!! Form::number('carton[]',null,['id'=>'carton_0','class'=>'form-control', 'readonly']); !!}
        </div>
        <div class="col-md-1">
            {!! Form::number('free_qty[]',null,['id'=>'free_qty_0','class'=>'form-control', 'readonly']); !!}
            {!! Form::hidden('free[]',null,['id'=>'free_0','class'=>'form-control', 'readonly']); !!}
        </div>
        <div class="col-md-2">
            <label for="amount"> Price Per Qty </label>
            {!! Form::number('price[]',null,['id'=>'price_0','class'=>'form-control input-number price','placeholder'=>' Price', 'step'=>"any", 'required']); !!}
        </div>
        <div class="col-md-2">
            <label for="amount"> Total Price </label>
            {!! Form::number('total_price[]',null,['id'=>'total_price_0','class'=>'form-control input-number total_price','placeholder'=>'Total Price', 'step'=>"any", 'required', 'readonly']); !!}
        </div>
        <div class="col-md-1">
            <label for="description">Description  </label>
            {!! Form::text('description[]',null,['id'=>'description','class'=>'form-control' ,'placeholder'=>'Enter Description','height'=>'70px !important' ]); !!}
        </div>
        <div class="col-md-1">
            <a class="btn btn-success add-row pull-right margin-top-3 margin-top-23" href="#" ><i class="fa fa-plus-circle"></i> </a>
        </div>
    </div>
</div>
<div class="col-md-12">
    <div class="">
        <div class="col-md-4">

            <div class="form-group">
                <label> Notation </label>
                {!! Form::textarea('notation',null,['id'=>'notation','class'=>'form-control','placeholder'=>'Enter notation']); !!}
            </div>

        </div>
        <div class="col-md-4">

            <div class="form-group">
                <label for="amount"> Total Bill </label>
                {!! Form::number('total_bill',0,['id'=>'total_bill','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"any", 'required', 'readonly']); !!}
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
    <script src="{{ asset('public/adminLTE/plugins/iCheck/icheck.min.js') }}"></script>


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
            $('.date').datepicker('setDate', today);

            $('.select2').select2();

            var products = [];
            products = <?php print_r(json_encode($products)); ?>;

            var i = 1;
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

                var html = '<div class="box-body requisition-purchase-box ">' +
                    '<div class="col-md-3">' +
                    '<select id="product_id_'+i+'" data-option="'+i+'" class="form-control select2 item-name"  name="product_id[]">'+optionName+'</select>' +
                    '</div> '+
                    '<div class="col-md-1">' +
                    '<input id="unit_'+i+'" disabled  min="0" class="form-control" name="unit[]" type="text">'+
                    '</div>' +
                    '<div class="col-md-1">' +
                    '<input id="last_purchase_qty_'+i+'" readonly class="form-control" name="last_purchase_qty[]" type="number">'+
                    '</div>'  +
                    '<div class="col-md-1">' +
                    '<input id="last_purchase_price_'+i+'" readonly class="form-control" name="last_purchase_price[]" type="number">'+
                    '</div>'  +
                    '<div class="col-md-1">' +
                    '<input id="stock_'+i+'" readonly class="form-control" name="available_stock[]" type="number">'+
                    '</div>' +
                    // '<div class="col-md-1">' +
                    // '<input id="sales_price_'+i+'" readonly class="form-control" name="sales_price[]" type="number">'+
                    // '</div>' +
                    '<div class="col-md-1">'+
                    '<input id="qty_'+i+'" class="form-control qty" min="0" name="qty[]" type="number">'+
                    '</div>'+
                    '<div class="col-md-1">' +
                    '<input placeholder=" Price"  min="0"  id="price_' + i + '" data-option="' + i + '" class="form-control price input-number" step="any" name="price[]"  type="number"/>' +
                    '</div>' +
                    '<div class="col-md-1">' +
                    '<input placeholder="Total Price" min="0"  id="price_' + i + '" data-option="' + i + '" class="form-control input-number total_price" step="any" name="total_price[]"  type="number"/>' +
                    '</div>' +
                    '<div class="col-md-1">' +
                    '{!! Form::text('description[]',null,['id'=>'description','class'=>'form-control' ,'placeholder'=>'Enter Description','height'=>'70px !important' ]); !!}' +
                    '</div>' +
                    '<div class="col-md-1"> <a class="btn btn-success add-row pull-right margin-top-3" href="#" ><i class="fa fa-plus-circle"></i> </a>' +
                    '</div>'+
                    '</div>';

                i++;

                $(this).parent().css("padding-top", '10px');
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
                var actionHtml = '<a href="#" class=" btn-text-info margin-top-23 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a>';
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
                for(var i = 1; i<=$('.requisition-purchase-box').length; i++) {
                    var qty = $('.requisition-purchase-box:nth-child(' + i + ') div:nth-child(6) input').val();
                    // var total_price = $('.requisition-purchase-box:nth-child(' + i + ') div:nth-child(8) input').val();

                    var price = $('.requisition-purchase-box:nth-child(' + i + ') div:nth-child(7) input').val();
                    price =  price == undefined || price == "" ? 0 : parseFloat(price);
                    var total_price = parseFloat(qty*price);

                    // qty =  qty == undefined || qty == "" ? 0 : parseInt(qty);
                    // total_price =  total_price == undefined || total_price == "" ? 0 : parseFloat(total_price);
                    // var price = parseFloat(total_price/qty).toFixed(2);

                    total_bill = total_bill+total_price;
                    // $('.requisition-purchase-box:nth-child(' + i + ') div:nth-child(7) input').val(price);
                    $('.requisition-purchase-box:nth-child(' + i + ') div:nth-child(8) input').val(total_price);
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
                discount = parseFloat(discount);

                var paid_amount = $('#paid_amount').val();

                unload_cost = unload_cost == '' ? 0 : unload_cost;
                transport_cost = transport_cost == '' ? 0 : transport_cost;
                paid_amount = paid_amount == '' ? 0 : paid_amount;

                total_bill = parseFloat(total_bill);
                unload_cost = parseFloat(unload_cost);
                transport_cost = parseFloat(transport_cost);
                paid_amount = parseFloat(paid_amount);
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
                var id = $(this).data('option');

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'item_id' : itemId
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
                            // $('#sales_price_'+id).val(data.price);
                            $('#stock_'+id).val(data.stock);
                            $('#unit_'+id).val(data.unit);
                            $('#price_'+id).val(data.price != null ? data.price : 0);

                            $('#free_'+id).val(data.free_qty != null ? data.free_qty : 0);
                            $('#pack_qty_'+id).val(data.pack_qty != null ? data.pack_qty : 0);
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

            //Flat red color scheme for iCheck
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass   : 'iradio_flat-green'
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

        });

    </script>

@endpush
