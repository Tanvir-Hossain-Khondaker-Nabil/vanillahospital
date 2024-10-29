<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/26/2019
 * Time: 10:37 AM
 */
?>
<script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- CK Editor -->
<script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

<!-- Date range picker -->
<script type="text/javascript">
    $(function () {
        $('#estimate_delivery_date').datepicker({
            "setDate": new Date(),
            "format": 'mm/dd/yyyy',
            "todayHighlight": true,
            "autoclose": true
        });
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


        $(".repair_type").change( function () {
            let repair_type = $('input[name=repair_type]:checked').val();

            if(repair_type == 1 )
            {
                $(".order_toggle").removeClass("hidden");
            }else{
                $("#product_td").html('<input id="product_name" class="form-control" name="product_name" type="text">');
                $(".order_toggle").addClass("hidden");
            }

        });

        $("#payment_option").change( function () {

            if(this.checked)
            {
                $('#discount_type').attr('readonly', true);
                $('#paid_amount').attr('readonly', true);
                $('#paid_amount').val(0);
                $('#discount').attr('readonly', true);
            }else{
                total_price_calculate();
                $('#discount_type').attr('readonly', false);
                $('#paid_amount').attr('readonly', false);
                $('#discount').attr('readonly', false);
            }

        });

        $('.input-group-addon.search').click(function(e){

                var sale_code = $('#order_number').val();

                var url = "{{ route('order_search') }}";

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'sale_code' : sale_code,
                    'type' : "single",
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
                    if(data.order_products)
                    {
                        $('#product_td').html(data.order_products);
                        $('#select_product').select2();
                    }
                    $('#orderDetails').removeClass("hidden");
                    $('#orderDetailsModal').html(data.pos_html);
                }else{
//                        console.log(data);
                    bootbox.alert("{{__('common.no_data_found')}}");
                }
            });

            e.stopImmediatePropagation();
            return false;
        });

        $(document).on('change','.cover_by', function(e) {
            e.preventDefault();

            var $div = $(this).parent().parent();
            if($(this).is(':checked'))
            {
                $div.find('td:nth-child(4)').find('input').val(0);
            }else{
                var ser_price = $div.find('td:nth-child(4)').find('input').data('price');
                $div.find('td:nth-child(4)').find('input').val(ser_price);
            }
            total_price_calculate();
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

        var serviceText = [];
        var productText = [];

        $(document).on('click', '#add-service', function () {

            var new_service = $("#new_service option:selected").val();
            var service_name = $("#new_service option:selected").text();
            var service_price = $("#new_service option:selected").data('price');


            if (new_service == "") {
                bootbox.alert("{{__("pleases_select_service")}}");
                return false;
            }


            if (serviceText.includes(new_service)) {
                bootbox.alert("{{ __('common.already_selected') }}");
                return false;
            }

            serviceText.push(new_service);

            var i = $(".add-service-parts tbody tr").length+1;

            var html = `<tr>
                            <td style="width: 27px; text-align: center;">`+i+`</td>
                            <td style="width: 200px;">`+service_name+`<input name="services[type][]" class="form-control" value="service" type="hidden"/><input name="services[id][]" class="form-control" value="`+new_service+`" type="hidden"/></td>
                            <td style="width: 50px; ">  <input name="services[qty][]" class="form-control qty" value="1" type="number"/></td>
                            <td style="width: 100px;">  <input name="services[price][]" class="form-control price"  value="`+service_price+`" data-price="`+service_price+`" step="any" type="number"/></td>
                            <td style="width: 100px; text-align: center;"> <input name="services[cover][]" class="cover_by"  value="1" type="checkbox"/></td>
                            <td style="width: 20px;"> <a href="javascript:void(0)" class="btn-text-danger delete-field"><i class="fa fa-close"></i></a></td>
                        </tr>`;


            $('input[type=number]').attr('min', 0);
            $(".add-service-parts tbody").append(html);
            total_price_calculate();
        });

        $(document).on('click', '#add-parts', function () {

            var new_product = $("#new_product option:selected").val();
            var service_name = $("#new_product option:selected").text();
            var item_price = $("#new_product option:selected").data('price');


            if (new_product == "") {
                bootbox.alert("{{__("pleases_select_service")}}");
                return false;
            }


            if (productText.includes(new_product)) {
                bootbox.alert("{{ __('common.already_selected') }}");
                return false;
            }

            productText.push(new_product);

            var i = $(".add-service-parts tbody tr").length+1;

            var html = `<tr>
                            <td style="width: 27px; text-align: center;">`+i+`</td>
                            <td style="width: 200px;">`+service_name+`<input name="services[id][]" class="form-control" value="`+new_product+`" type="hidden"/><input name="services[type][]" class="form-control" value="product" type="hidden"/></td>
                            <td style="width: 50px; ">  <input name="services[qty][]" class="form-control qty" value="1" type="number"/></td>
                            <td style="width: 100px;">  <input name="services[price][]" class="form-control price"  value="`+item_price+`"  data-price="`+item_price+`" step="any" type="number"/></td>
                            <td style="width: 100px; text-align: center;"> <input name="services[cover][]"  class="cover_by" value="1" type="checkbox"/></td>
                            <td style="width: 20px;"> <a href="javascript:void(0)" class="btn-text-danger delete-field"><i class="fa fa-close"></i> </a></td>
                        </tr>`;


            $('input[type=number]').attr('min', 0);
            $(".add-service-parts tbody").append(html);
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


        $(document).on('change','.price', function(e) {

            e.preventDefault();
            var price = parseFloat($(this).val());
            var purchase_price = parseFloat($('#purchase_price').val());

            if(parseFloat(itemPrice)> price && price < purchase_price )
            {
                bootbox.alert("{{__('common.sales_price_cant_be_low')}}");
                $(this).val(purchase_price);
                return false;
            }

        });


        $(document).on('keyup','#discount, #paid_amount', function(e) {
            e.preventDefault();

            total_price_calculate();
        });

        function price_calculate(){

            var $tr = $('.add-service-parts tbody');
            var total_discount = 0;
            var total_bill = 0;
            for(var i = 1; i<=$tr.find('tr').length; i++) {
                var qty = $tr.find('tr:nth-child('+i+') td:nth-child(3) input').val();
                var price = $tr.find('tr:nth-child('+i+') td:nth-child(4) input').val();
                qty =  qty == undefined || qty == "" ? 0 : parseFloat(qty);
                price =  price == undefined || price == "" ? 0 : parseFloat(price);
                var total_price = (qty*price);

                total_bill = (total_bill+total_price);
            }
            total_bill = total_bill.toFixed(2);
            $('#sub_total').val(total_bill);
        }

        function total_price_calculate()
        {
            price_calculate();

            var total_bill = $('#sub_total').val();
            var discount = $('#discount').val();
            var discount_amount = 0;
            discount = parseFloat(discount);

            var paid_amount = $('#paid_amount').val();

            paid_amount = paid_amount == '' ? 0 : paid_amount;

            total_bill = parseFloat(total_bill);
            paid_amount = parseFloat(paid_amount);

            var total_amount = total_bill;

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

        $("#customer_phone").blur( function(e){
            e.preventDefault();
            var url = "{{ route('search.customer_phone') }}";
            var customer_phone = $(this).val();

            if(customer_phone != "")
            {

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'customer_phone' : customer_phone
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
                        var credit = data.customer != null ? data.customer.point : 0;
                        var id = data.customer != null ? data.customer.id : null;
                        var phone = data.customer != null ? data.customer.phone : null;
                        var membership = data.customer != null ? data.customer.membership_no : null;
                        var name = data.customer != null ? data.customer.name : null;


                        var member_info = "<label class='font-weight-normal mb-0'>Customer Name: <span class='text-bold'>"+name+"</span></label><br/><label class='font-weight-normal mb-0'>Customer Point: <span class='text-bold'>"+credit.toFixed(2)+"</span></label ><br/>";


                        // $('#member_info').html(member_info);
                        $('#membership').val(membership);
                        $('#member_point').html("( Point= "+credit.toFixed(2)+" )");
                        $('#customer_id').val(id);

                        if(name == phone)
                        {
                            $('#customer_name').val("");
                        }else{
                            $('#customer_name').val(name);
                        }

                    }
                });
            }
        });
    });
</script>

