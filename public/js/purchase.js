// var date = new Date();
$(function () {

    var i = 1;
    $(document).on('click', '.add-row', function () {

        var $div = $(this).parent().parent('.box-body').parent('.box');
        var childrenDiv = $div.children();

        var html = '<div class="box-body purchase-box ">' +
            '<div class="col-md-3">' +
            '<label for="category_id">Item Name </label>' +
            '<select id="product_id_'+i+'" data-option="'+i+'" class="form-control select2 item-name" required="" name="product_id[]">'+optionName+'</select>' +
            '</div> '+
            '<div class="col-md-1">' +
            '<label for="amount"> Unit </label>' +
            '<input id="unit_'+i+'" readonly  min="0" class="form-control" name="unit[]" type="text">'+
            '</div>' +
            '<div class="col-md-1">' +
            '<label for="amount"> Last Purchase Qty </label>' +
            '<input id="last_purchase_qty_'+i+'" disabled class="form-control" name="last_purchase_qty_0" type="number">'+
            '</div>'  +
            '<div class="col-md-1">' +
            '<label for="amount"> Available Stock </label>' +
            '<input id="stock_'+i+'" disabled class="form-control" name="stock" type="number">'+
            '</div>' +
            '<div class="col-md-1">'+
            '<label for="amount"> Qty </label>'+
            '<input id="qty_'+i+'" class="form-control qty" min="0" required="" name="qty[]" type="number">'+
            '</div>'+
            '<div class="col-md-1">' +
            '<label for="price">  Price </label>' +
            '<input placeholder=" Price"  min="0"  id="price_' + i + '" data-option="' + i + '" class="form-control price input-number" step="0.000" name="price[]" required type="number"/>' +
            '</div>' +
            '<div class="col-md-1">' +
            '<label for="price"> Total Price </label>' +
            '<input placeholder="Total Price" min="0"  id="price_' + i + '" data-option="' + i + '" class="form-control input-number" step="0.000" name="total_price[]" required type="number"/>' +
            '</div>' +
            '<div class="col-md-2">' +
            '<label for="description">Description  </label>' +
            '<textarea placeholder="Description" class="form-control" name="description[]"></textarea>' +
        '</div>' +
        '<div class="col-md-1"> <a class="btn btn-success add-row pull-right margin-top-23" href="#" ><i class="fa fa-plus-circle"></i> </a>' +
        '</div>'+
        '</div>';

        i++;

        $(this).parent().css("padding-top", '33px');
        // console.log(childrenDiv);
        childrenDiv.find('input').attr('readonly', true);
        childrenDiv.find('select').attr('readonly', true);
        var actionHtml = '<a href="#" class="btn-text-info margin-top-23 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a></span>';
        $(this).parent('div').html(actionHtml);

        $('input[type=number]').attr('min', 0);
        $div.append(html);
        $('.select2').select2();

    });

    $(document).on('click','.edit-button', function() {

        var $div = $(this).parent().parent('.box-body');
        var childrenDiv = $div.children();
        childrenDiv.find('input').attr('readonly', false);
        childrenDiv.find('select').attr('readonly', false);
        $(this).parent().css("padding-top", '0px');
        var actionHtml = '<a class="btn btn-warning pull-right margin-top-23" id="edit-complete" href="#" ><i class="fa fa-check-circle"></i> </a>';
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
        $(this).parent().css("padding-top", '33px');
        childrenDiv.find('input').attr('readonly', true);
        childrenDiv.find('select').attr('readonly', true);
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
        for(var i = 1; i<=$('.purchase-box').length; i++) {
            var qty = $('.purchase-box:nth-child(' + i + ') div:nth-child(5) input').val();
            var price = $('.purchase-box:nth-child(' + i + ') div:nth-child(6) input').val();
            qty =  qty == undefined || qty == "" ? 0 : parseInt(qty);
            price =  price == undefined || price == "" ? 0 : parseFloat(price);
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


});
