<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/22/2020
 * Time: 5:45 PM
 */

?>

<script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<!-- CK Editor -->
<script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

<!-- Date range picker -->
<script type="text/javascript">
    $(function () {


        // $(".sidebar-toggle").trigger("click");
        // $('body').addClass("sidebar-collapse");

        $('.collapse').collapse({
            toggle: false
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

        var i = 1;
        let sale_product = [];

        function  product_details(product_id, product_name) {


            var form_data = {
                '_token' : "{{ csrf_token() }}",
                'item_id' : product_id,
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
                        if(data.stock>0)
                        {

                            let inList = sale_product.indexOf(data.id);

                            if(inList>=0)
                            {
                                let qty = $("#qty_"+data.id).val();
                                var stock_has = parseFloat($("#stock_"+data.id).val());

                                if(stock_has>=parseFloat(qty)+1)
                                    $("#qty_"+data.id).val(parseFloat(qty)+1);
                                else
                                    bootbox.alert("{{__('common.out_of_stock')}} ");


                            }else {
                                i = $("#sales_point tr").length+1;
                                var html = '<tr > <td>' + i + '</td> <td>' + product_name + '<input type="hidden" name="product_id[]" value="' + data.id + '" /> <input type="hidden" id="stock_' + data.id + '" value="' + data.stock + '"  /></td> ' +
                                    '<td class="text-center">'+data.stock+'</td> ' + '' +
                                    '<td><input type="number" min="0" data-option="' + data.id + '" id="qty_' + data.id + '" name="qty[]"  min="0" step="any" value="1" class="qty text-right px-2" style="width: 60px; "></td> ' + '' +
                                    '<td><input type="number" step="any"  min="0" name="price[]" readonly  value="' + (data.price != null ? data.price : 0) + '" id="price_' + data.id + '" data-option="' + data.id + '" class="price text-right px-2" style="width: 80px; "></td> ' +
                                    '<td><input type="number" step="any"  min="0" name="total_price[]"  value="' + (data.price != null ? data.price : 0) + '" id="total_price_' + data.id + '" data-option="' + data.id + '" class="total_price text-right px-2" style="width: 100px; "></td> ' +
                                    '<td><a  tabindex="-1" href="javascript:void(0)"  data-content="' + data.id +'"  class="delete-field" style="color: red;"><i class="fa fa-trash"></i></a> </td>'
                                    + '</tr>';

                                sale_product.push(data.id);
                                $("#sales_point").append(html);
                            }
                            total_price_calculate();

                            i++;


                            $('#barcode_search').html("");
                            $('.search_product_list').html("");
                            $('#search_product').css("display", 'none');
                        }else{
                            bootbox.alert(data.item_name+" {{__('common.out_of_stock')}} ");
                        }
                    }
                });


            $('#showlist').css('margin-top','0px');
        }


        $(document).on('click', '.add-row', function (e) {
            e.preventDefault();

            var product_id  = $(this).data('target');
            var product_name  = $(this).data('value');

            product_details(product_id, product_name);
        });


        var searchUrl =  "{{ route('search.item') }}";

        var base_url = "{{ asset('/') }}";

        $("#barcode_search").on("keyup", function() {

            var product = $(this).val();

            var form_data = {
                '_token' : "{{ csrf_token() }}",
                'product' : product,
            };

            if(product.length>1)
            {
                $.ajax({
                    type        : 'POST',
                    url         : searchUrl, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                })
                // using the done promise callback
                    .done(function(data) {

                        if(data.status == "success")
                        {
                            $("#search_product").css('display','block');

                            var html ="";
                            var showHtml ="";

                            $.each( data.items, function( key, value ) {
                                html += "<li class='px-3 add-row' data-stock='"+value.stock_details.stock+"'  data-value='"+value.item_name+"'  data-skucode='"+value.skuCode+"'  data-pcode='"+value.productCode+"'  data-unit='"+value.unit+"' data-price='"+value.price+"' data-target='"+value.id+"'><input class='border-0 w-90 item-focus'  data-stock='"+value.stock_details.stock+"'  data-value='"+value.item_name+"'  data-skucode='"+value.skuCode+"'  data-pcode='"+value.productCode+"'  data-unit='"+value.unit+"' data-price='"+value.price+"' data-target='"+value.id+"' value='"+value.item_name+"' /> <span class='pull-right'>"+value.stock_details.stock+"</span></li>";

                            });

                            $('.search_product_list').html(html);
                            $('#showlist').css('margin-top','110px');
                            $('#showlist').html(data.pos_html);
                        }else{
                            $("#search_product").css('display','none');

                        }
                    });
            }else{
                $(".search_product_list").html('');
                $("#search_product").css('display','none');
            }



        });

        var enterPressed = 0;
        window.onkeypress = function (e) {
            var keyCode = (e.keyCode || e.which);
            if (keyCode === 13 && $("#paid_amount").val() != "") {
                enterPressed++;
                    if(enterPressed>=2)
                    {
                        $( "#sale_form" ).submit();
                    }
            }
        };


        document.addEventListener('keypress', (e) => {
            if (e.which == 13 && $('.item-focus:focus').val() != "") {
                e.preventDefault();

                var product_name = $('.item-focus:focus').val();
                var product_id = $('.item-focus:focus').data('target');


                product_details(product_id, product_name);

                $('#barcode_search').val('');
                document.getElementById("barcode_search").focus();

                return false;
            }
        });


        // document.addEventListener('keydown', (e) => {
        //     if (e.which == 9 && $('#barcode_search').val() != "") {
        //         $('#barcode_search').val('');
        //         $(".search_product_list").html('');
        //         $("#search_product").css('display','none');
        //     }
        // });

        var inList=0;
        var set = 0;


        document.addEventListener('keydown', (e) => {

            if ( e.which == 109  && e.ctrlKey && e.shiftKey ) {

                e.preventDefault();


                var trRow = inList;

                if(trRow == 0)
                {
                    var qtyTd = $('input.qty').focus();
                    var tr = qtyTd.parent().parent().length;

                }else{
                    var tr = trRow;
                }

                if(tr>=0)
                {
                    $('#sales_point').find('tr:nth-child('+tr+') td:nth-child(4) input').focus();
                    tr = tr-1;
                    inList = tr;
                }else{

                    var tr = $('#sales_point').find('tr').length;
                    $('#sales_point').find('tr:nth-child('+tr+') td:nth-child(4) input').focus();
                    tr = tr-1;
                    inList = tr;

                }

            }

            if (e.which == 107  && e.ctrlKey && e.shiftKey ) {

                e.preventDefault();


                var trRow = inList;

                if(trRow == 0)
                {
                    var qtyTd = $('input.qty').focus();
                    var tr = qtyTd.parent().parent().length;

                }else{
                    var tr = trRow;
                }

                if(tr>$('#sales_point').find('tr').length)
                {
                    tr = 0;
                    $('#sales_point').find('tr:nth-child('+tr+') td:nth-child(4) input').focus();
                    tr = tr+1;
                    inList = tr;
                }else{
                    $('#sales_point').find('tr:nth-child('+tr+') td:nth-child(4) input').focus();
                    tr = tr+1;
                    inList = tr;
                }

            }

            if (e.which == 83 && e.ctrlKey && e.shiftKey) {

                e.preventDefault();
                // Add your code here
                document.getElementById("barcode_search").focus();
            }

            if (e.which == 68 && e.ctrlKey && e.shiftKey) {

                e.preventDefault();
                // Add your code here
                document.getElementById("date").focus();
            }

            if (e.which == 97 && e.ctrlKey && e.altKey) {

                e.preventDefault();
                // Add your code here

                $('#sales_point').find('tr:nth-child(1) td:nth-child(4) input').focus();

            }

            if (e.which == 96 && e.ctrlKey && e.altKey) {

                e.preventDefault();
                // Add your code here
                var tr = $('#sales_point').find('tr').length;

                $('#sales_point').find('tr:nth-child('+tr+') td:nth-child(4) input').focus();

            }

            if (e.which == 80 && e.ctrlKey && e.shiftKey) {

                e.preventDefault();
                // Add your code here
                document.getElementById("paid_amount").focus();
            }

        });

        $("#paid_amount").val("");
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

        $('#barcode_search').keypress(function(event){

            var keycode = (event.keyCode ? event.keyCode : event.which);

            if(keycode == 13 ) {

                event.preventDefault();
                var productCode = $('#barcode_search').val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'product_code' : productCode,
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
                            if(data.stock>0)
                            {

                                let inList = sale_product.indexOf(data.id);

                                if(inList>=0)
                                {
                                    var stock_has = parseFloat($("#stock_"+data.id).val());

                                    if(stock_has>=parseFloat(qty)+1)
                                        $("#qty_"+data.id).val(parseFloat(qty)+1);
                                    else
                                        bootbox.alert("{{__('common.out_of_stock')}} ");

                                }else {
                                    i = $("#sales_point tr").length+1;
                                    var html = '<tr > <td>' + i + '</td> <td>' + data.item_name + '<input type="hidden" name="product_id[]" value="' + data.id + '" /> <input type="hidden" id="stock_' + data.id + '" value="' + data.stock + '"  /></td> ' +
                                        '<td class="text-center">'+data.stock+'</td> ' + '' +
                                        '<td><input type="number" min="0" data-option="' + data.id + '" id="qty_' + data.id + '" name="qty[]"  min="0" value="1" class="qty text-right px-2" style="width: 60px; "></td> ' + '' +
                                        '<td><input tabindex="-1" type="number" min="0" name="price[]" value="' + (data.price != null ? data.price : 0) + '" id="price_' + data.id + '" data-option="' + data.id + '" class="price text-right px-2" style="width: 80px; "></td> ' +
                                        '<td><input tabindex="-1" type="number" min="0" name="total_price[]" value="' + (data.price != null ? data.price : 0) + '" id="total_price_' + data.id + '" data-option="' + data.id + '" class="total_price text-right px-2" style="width: 80px; "></td> ' +
                                        '<td><a tabindex="-1" href="javascript:void(0)"  data-content="' + data.id +'"  class="delete-field" style="color: red;"><i class="fa fa-trash"></i></a> </td>'
                                        + '</tr>';


                                    sale_product.push(data.id);
                                    $("#sales_point").append(html);
                                }
                                total_price_calculate();

                                i++;
                            }else{
                                bootbox.alert(data.item_name+" {{__('common.out_of_stock')}} ");
                            }
                        }
                    });

                $('#barcode_search').val('');

                // $('#barcode_search').html("");
                $('.search_product_list').html("");
                $('#search_product').css("display", 'none');

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

                    }
                });

        });

        $(document).on('click','.submit_change', function(e) {
            var $submit = $(this).data('target');
            $("#submit_type").val($submit);
        });

        $(document).on('click','.delete-field', function(e) {
            e.preventDefault();

            var productId = $(this).data('content');
            sale_product.splice($.inArray(productId, sale_product), 1);
            var $div = $(this).parent().parent();
            $div.remove();
            total_price_calculate();
        });


        $(document).on('keyup','.qty, .total_price', function(e) {
            e.preventDefault();

            total_price_calculate();
        });

        $(document).on('keyup','.qty', function(e) {

                if (e.keyCode === 69 || e.keyCode === 109 ) {
                    e.preventDefault();
                    $(this).val(1);
                    total_price_calculate();
                }else{

                    e.preventDefault();
                    var $divID = $(this).data('option');
                    var stock = $("#stock_" + $divID).val();
                    var qtyCheck = $(this).val();
                    if (parseFloat(stock) < $(this).val()) {
                        $(this).val(qtyCheck);
                        bootbox.alert("{{__('common.sales_quantity_cant_cross_available_stock')}}");
                        return false;
                    }
                }



        });

        $(document).on('keyup','#shipping_charge, #discount, #paid_amount', function(e) {
            e.preventDefault();
            if (e.keyCode === 69 || e.keyCode === 109 ) {
                e.preventDefault();
                $(this).val(0);
            }else {
                total_price_calculate();
            }
        });

        $(document).on('change','#discount_type', function(e) {
            e.preventDefault();
            total_price_calculate();
        });

        function price_calculate(){

            var $tr = $('#sales_point');
            var total_bill = 0;
            for(var i = 0; i<=$tr.find('tr').length; i++) {
                var qty = $tr.find('tr:nth-child('+i+') td:nth-child(4) input').val();
                // var price = $tr.find('tr:nth-child('+i+') td:nth-child(5) input').val();
                var total_price = $tr.find('tr:nth-child('+i+') td:nth-child(6) input').val();

                qty =  qty == undefined || qty == "" ? 0 : parseFloat(qty);
                // price =  price == undefined || price == "" ? 0 : parseFloat(price);
                total_price =  total_price == undefined || total_price == "" ? 0 : parseFloat(total_price);

                // var total_price = qty*price;
                var price = total_price/qty;

                $tr.find('tr:nth-child('+i+') td:nth-child(5) input').val(price.toFixed(2));

                total_bill = (total_bill+total_price);
            }
            $('#sub_total').val(total_bill.toFixed(2));
        }

        function total_price_calculate()
        {
            price_calculate();

            var total_bill = $('#sub_total').val();

            var shipping_charge = $('#shipping_charge').val() == "" ? parseFloat(0).toFixed(2) : parseFloat($('#shipping_charge').val()).toFixed(2);
            var discount = $('#discount').val() == "" ? parseFloat(0).toFixed(2) : parseFloat($('#discount').val()).toFixed(2);
            var discount_type = $('#discount_type').val();
            var discount_amount = 0;
            discount = parseFloat(discount);

            var paid_amount = $('#paid_amount').val();

            paid_amount = paid_amount == '' ? 0 : paid_amount;

            total_bill = parseFloat(total_bill).toFixed(2);
            paid_amount = parseFloat(paid_amount);
            discount_amount = parseFloat(discount_amount);

            var total_amount = parseFloat(total_bill)+parseFloat(shipping_charge);
            total_amount = parseFloat(total_amount).toFixed(2);


            if(discount_type=="fixed")
                discount_amount = discount;
            else
                discount_amount = total_bill*discount/100;

            var amount_to_pay = total_amount-discount_amount;
            var due = amount_to_pay-paid_amount;

            if(due>0)
            {
                $('#customer_phone').attr('required', true);
                $('#customer_id').attr('required', true);
            } else{
                $('#customer_phone').attr('required', false);
                $('#customer_id').attr('required', false);
            }


            discount_amount = discount_amount.toFixed(2);
            amount_to_pay = amount_to_pay.toFixed(2);

            var duetext = 0;
            if(due<0)
            {
                duetext = due*(-1);
                $('#change_amount').text(duetext.toFixed(2));
                $('#due_amount').text(0);
                $('#paid_amt').val(amount_to_pay);
            }else{
                duetext = due;
                $('#due_amount').text(duetext.toFixed(2));
                $('#change_amount').text(0);
                $('#paid_amt').val(paid_amount);
            }

            $('#total_sub_price').text(total_bill);
            $('#total_price_amount').text(total_amount);
            $('#total_ship').text(shipping_charge);
            $('#discount_amount').text(discount_amount);
            $('#amt_to_pay').text(amount_to_pay);

            $('#amount_to_pay').val(amount_to_pay);
            $('#total_amount').val(amount_to_pay);
        }


        $("#membership").blur( function(e){
            e.preventDefault();
            var url = "{{ route('search.membership') }}";
            var membership = $(this).val();

            if(membership != "")
            {

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'membership' : membership
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
                        var membership = data.customer != null ? data.customer.membership_no : null;
                        var name = data.customer != null ? data.customer.name : null;
                        var phone = data.customer != null ? data.customer.phone : null;

                        var member_info = "<label class='font-weight-normal mb-0'>Customer Name:</span><span class='text-bold'>"+name+"<span class='text-bold'></label><br/><label class='font-weight-normal mb-0'>Customer Point:<span class='text-bold'>"+credit.toFixed(2)+"</span></label ><br/>";


                        // $('#member_info').html(member_info);
                        $('#membership').val(membership);
                        $('#customer_phone').val(phone);
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

            var data_message = "";
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
                            data_message = data;
                        }else{
                            bootbox.alert(data.item_name+" {{__('common.out_of_stock')}} ");
                        }
                    }
                });

            return data_message;
        }


        $("#paid_amount").keyup( function (e) {
            e.preventDefault();

            var price = parseFloat($(this).val());
            var total_amount = parseFloat($(".amount_to_pay").val());

            // if(price>total_amount){
            //     bootbox.alert("Paid amount can't be bigger than Amount to Pay");
            //     $(this).val('');
            //     return false;
            // }else{
                total_price_calculate();
            // }
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


// Mouseover/ Click sound effect- by JavaScript Kit (www.javascriptkit.com)

        var html5_audiotypes={ //define list of audio file extensions and their associated audio types. Add to it if your specified audio file isn't on this list:
            "mp3": "audio/mpeg",
            "mp4": "audio/mp4",
            "ogg": "audio/ogg",
            "wav": "audio/wav"
        }

        function createsoundbite(sound){
            var html5audio=document.createElement('audio')
            if (html5audio.canPlayType){ //check support for HTML5 audio
                for (var i=0; i<arguments.length; i++){
                    var sourceel=document.createElement('source')
                    sourceel.setAttribute('src', arguments[i])
                    if (arguments[i].match(/\.(\w+)$/i))
                        sourceel.setAttribute('type', html5_audiotypes[RegExp.$1])
                    html5audio.appendChild(sourceel)
                }
                html5audio.load()
                html5audio.playclip=function(){
                    html5audio.pause();
                    html5audio.currentTime=0;
                    html5audio.play();
                }
                return html5audio
            }
            else{
                return {playclip:function(){throw new Error("Your browser doesn't support HTML5 audio unfortunately")}}
            }
        }



        var soundMp3 = "{{ asset('public/pos_assets/click.mp3') }}";
        var soundOgg = "{{ asset('public/pos_assets/click.ogg') }}";

        var clicksound=createsoundbite( soundOgg, soundMp3);

            // $("#audiotag1").play();
        clicksound.playclip();


    });


    $( window ).load(function() {
        $(".skin-blue").addClass("sidebar-collapse");
    });
</script>


