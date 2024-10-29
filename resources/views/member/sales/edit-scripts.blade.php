<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/27/2019
 * Time: 4:27 PM
 */
?>


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

        // $('.select2').select2();
        $('.select2').select2({ width: 'element' });


        var products = [];
        products = <?php print_r(json_encode($products)); ?>;

        {{--var $customers = [];--}}

        {{--var optionName;--}}
        {{--function customer() {--}}

        {{--optionName='<option selected="selected" value="">{{__("common.select_customer_name")}}</option>';--}}

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
                bootbox.alert("{{__('common.please_select_item_name_or_input_value_qty_price')}}");
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
                '<td class="description">' +
                '{!! Form::text('product_info[]', null,[ 'class'=>'form-control check_serial']); !!}' +
                '</td>' +
                '<td><input id="unit_'+i+'" class="form-control" name="unit[]" readonly="" type="text">' +
                '</td>' +
                '<td><input id="stock_'+i+'" class="form-control"  name="available_stock[]" readonly="" type="text"></td>' +
                '<td><input id="qty_'+i+'" class="form-control input-number qty" step="any" name="qty[]" type="number" min="0"></td>' +
                '<td><input id="price_'+i+'" class="form-control input-number price" step="any" name="price[]" type="number" min="0"></td>' +
                '<td><input id="total_price_'+i+'" class="form-control total_price" step="any" name="total_price[]" type="number" min="0", readonly=""></td>' +
                '<td><input id="discount_'+i+'" class="form-control input-number discount" name="per_discount[]"  type="number" min="0"></td>' +
                '<td><input id="total_price_discount_'+i+'" class="form-control total_price_discount" step="any" name="total_price_discount[]" type="number" min="0", readonly=""></td>' +
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
            mouseWheelOff();
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
            $div.find('td:nth-child(8) input').attr('readonly', true);
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
            $mainDiv.last().find('td:nth-child(8) input').attr('readonly', true);
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


        $(document).on('keyup','.qty, .price, .discount', function(e) {
            e.preventDefault();
            total_price_calculate();
        });

        $(document).on('keyup','.qty', function(e) {
            e.preventDefault();

            var $div = $(this).parent().parent();
            var stock = $div.find('td:nth-child(3)').find('input').val();
            if(parseFloat(stock)<parseFloat($(this).val()))
            {
                $(this).val('');
                bootbox.alert("{{__('common.sales_quantity_cant_cross_available_stock')}}");
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
            var total_bill  = 0;
            var total_discount  = 0;
            for(var i = 1; i<=$tr.find('tr').length; i++) {
                var qty = $tr.find('tr:nth-child('+i+') td:nth-child(5) input').val();
                var price = $tr.find('tr:nth-child('+i+') td:nth-child(6) input').val();
                var discount = $tr.find('tr:nth-child('+i+') td:nth-child(8) input').val();
                var total_price = $tr.find('tr:nth-child('+i+') td:nth-child(7) input').val();
                qty =  qty == undefined || qty == "" ? 0 : parseFloat(qty);
                price =  price == undefined || price == "" ? 0 : parseFloat(price);
                discount =  discount == undefined || discount == "" ? 0 : parseFloat(discount);
                total_price =  total_price == undefined || total_price == "" ? 0 : parseFloat(total_price);
                total_price = (qty*price);
                var total_price_discount = (qty*price)-discount;
                 total_discount += discount;
                $tr.find('tr:nth-child('+i+') td:nth-child(7) input').val(total_price.toFixed(2));
                $tr.find('tr:nth-child('+i+') td:nth-child(9) input').val(total_price_discount.toFixed(2));

                total_bill = (total_bill+total_price);
            }
            total_bill = total_bill.toFixed(2);
            $('#sub_total').val(total_bill);
            $('#discount').val(total_discount);
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

            // alert(discount);

            var paid_amount = $('#paid_amount').val();

            paid_amount = paid_amount == '' ? 0 : paid_amount;

            total_bill = parseFloat(total_bill);
            paid_amount = parseFloat(paid_amount);

            var total_amount = total_bill+labor_cost+transport_cost;

            // if(discount_type=="fixed")
                discount_amount = discount;
            // else
            //     discount_amount = total_bill*discount/100;

            discount_amount = parseFloat(discount_amount);
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
                    bootbox.alert("Unable to Save");
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

