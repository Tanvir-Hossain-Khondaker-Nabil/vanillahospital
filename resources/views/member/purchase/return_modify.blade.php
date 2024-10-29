<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/20/2019
 * Time: 2:52 PM
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
    'heading' => 'Purchase Return',
];

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



@extends('layouts.back-end.master', $data)


@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

        @include('common._alert')

        @include('common._error')
        <!-- general form elements -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Purchase Return</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::model($modal, ['route' => ['member.purchase_return.update', $modal],  'method' => 'put', 'role'=>'form', 'files'=>true,'id'=>'update']) !!}

                <div class="box-body">

                    <div class="box">
                        <div class="box-body">
                            <div class="col-md-2">
                                <label for="suppliers"> Date </label>
                                {!! Form::text('date', month_date_year_format($modal->date),['id'=>'date', 'class'=>'form-control','required', 'readonly']); !!}
                            </div>
                            <div class="col-md-3">
                                <label for="supplier_id"> Supplier Name </label>
                                {!! Form::select('supplier_id', $suppliers, null,['id'=>'supplier_id', 'class'=>'form-control select2','required', 'placeholder'=>'Select Supplier Name', 'readonly']); !!}
                                <div id="current-balance">  </div>
                            </div>
                            <div class="col-md-2">
                                <label for="memo_no"> Memo No. </label>
                                {!! Form::text('memo_no', $memo_no, [ 'class'=>'form-control','required', 'readonly']); !!}
                            </div>
                            <div class="col-md-2">
                                <label for="chalan_no"> Chalan No. </label>
                                {!! Form::text('chalan', $chalan_no, ['class'=>'form-control','required', 'readonly']); !!}
                            </div>
                            <div class="col-md-3">
                                <label for="invoice_no"> Invoice No. </label>
                                {!! Form::text('invoice_no', null, ['class'=>'form-control','required']); !!}
                            </div>
                        </div>
                    </div>

                    <div class="box">

                        @foreach( $modal->purchase_details as $value)
                            <div class="box-body purchase-return-div">
                                <div class="col-md-2">
                                    <input type="hidden" name="purchaseDetails[]" value="{{ $value->id }}">
                                    <label for="product_id">Item Name </label>
                                    {!! Form::select('product_id[]', $products, $value->item_id,['class'=>'form-control select2 item-name','required', 'placeholder'=>'Select Item Name', 'readonly']); !!}
                                </div>

                                <div class="col-md-1">
                                    <label for="amount"> Unit </label>
                                    {!! Form::text('unit[]',$value->unit,['class'=>'form-control', 'readonly']); !!}
                                </div>
                                <div class="col-md-1">
                                    <label for="amount"> Available Stock </label>
                                    {!! Form::number('stock',null,['class'=>'form-control', 'disabled' => 'disabled']); !!}
                                </div>
                                <div class="col-md-1">
                                    <label for="amount"> Purchase Qty </label>
                                    {!! Form::number('p_qty[]',$value->qty,['class'=>'form-control ', 'required', 'readonly']); !!}
                                </div>
                                <div class="col-md-1">
                                    <label for="amount"> Price </label>
                                    {!! Form::number('per_price[]',$value->price,['class'=>'form-control input-number','placeholder'=>' Price', 'step'=>"0.000", 'required', 'readonly']); !!}
                                </div>
                                <div class="col-md-1">
                                    <label for="amount"> Total Price </label>
                                    {!! Form::number('t_price[]',$value->qty*$value->price,['class'=>'form-control input-number','placeholder'=>'Total Price', 'step'=>"0.000", 'required', 'readonly']); !!}
                                </div>
                                <div class="col-md-1">
                                    <label for="amount"> Return Qty </label>
                                    {!! Form::number('qty[]', null,['class'=>'form-control input-number qty','placeholder'=>'Return Qty', 'step'=>"0.000"]); !!}
                                </div>
                                <div class="col-md-1">
                                    <label for="amount"> Return Price </label>
                                    {!! Form::number('price[]', $value->price,['class'=>'form-control input-number price','placeholder'=>'Return Price', 'step'=>"0.000"]); !!}
                                </div>
                                <div class="col-md-1">
                                    <label for="amount"> Total Return Price </label>
                                    {!! Form::number('single_total_price[]', null,['class'=>'form-control input-number','placeholder'=>'Return Price', 'step'=>"0.000", 'readonly']); !!}
                                </div>
                                <div class="col-md-2">
                                    <label for="description">Description  </label>
                                    {!! Form::text('desc[]',null,['class'=>'form-control' ,'placeholder'=>'Enter Description','height'=>'70px !important']); !!}
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
        <div class="box">
            <div class="box-body">
                <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="notation">Notation  </label>
                            {!! Form::textarea('notation',null,['id'=>'notation','class'=>'form-control' ,'placeholder'=>'Enter Notation','height'=>'70px !important' ]); !!}
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="payment_method_id"> Payment Method  </label>
                            {!! Form::select('payment_method_id', $payment_methods, null,['readonly','class'=>'form-control select2 ','required']); !!}
                        </div>
                        <div class="form-group">
                            <label for=file> Attach if File Available  </label>
                            <input  name="attach" type="file" accept="image/*"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="product_id">Cash or Bank Account  </label>
                            {!! Form::select('cash_or_bank_id', $banks, null,['readonly','class'=>'form-control select2 ','required', 'placeholder'=>'Select Account Name']); !!}
                        </div>
                        <div class="form-group">
                            <label for="amount"> Total Bill </label>
                            {!! Form::number('total_bill',$modal->total_price,['id'=>'total_bill','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"0.000", 'required', 'readonly']); !!}
                        </div>
                        <div class="form-group">
                            <label for="amount"> Return Total Value </label>
                            {!! Form::number('return_total_value',0,['class'=>'form-control input-number return_total_value','placeholder'=>'Enter Price', 'step'=>"0.000", 'required', 'readonly']); !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <div class="col-md-12 text-right">
                    <button type="submit" id="submit" class="btn btn-primary">Update</button>
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

                optionName='<option selected="selected" value="">Select Item Name</option>';
                $.each( products, function( key, value ) {
                    optionName = optionName+'<option value="'+key+'" >'+value+'</option>';
                });

                var html = '<div class="box-body purchase-box ">' +
                    '<div class="col-md-3">' +
                    '<input type="hidden" name="purchaseDetails[]" value="new">' +
                    '<label for="category_id">Item Name </label>' +
                    '<select id="product_id_'+i+'" data-option="'+i+'" class="form-control select2 item-name" name="product_id[]">'+optionName+'</select>' +
                    '</div> '+
                    '<div class="col-md-1">' +
                    '<label for="amount"> Unit </label>' +
                    '<input id="unit_'+i+'" disabled  min="0" class="form-control" name="unit[]" type="text">'+
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
                    '<input id="qty_'+i+'" class="form-control qty" min="0"  name="qty[]" type="number">'+
                    '</div>'+
                    '<div class="col-md-1">' +
                    '<label for="price">  Price </label>' +
                    '<input placeholder=" Price"  min="0"  id="price_' + i + '" data-option="' + i + '" class="form-control price input-number" step="0.000" name="price[]"  type="number"/>' +
                    '</div>' +
                    '<div class="col-md-1">' +
                    '<label for="price"> Total Price </label>' +
                    '<input placeholder="Total Price" min="0"  id="price_' + i + '" data-option="' + i + '" class="form-control input-number" step="0.000" name="total_price[]" type="number"/>' +
                    '</div>' +
                    '<div class="col-md-2">' +
                    '<label for="description">Description  </label>' +
                    '{!! Form::text('description[]',null,['id'=>'description','class'=>'form-control' ,'placeholder'=>'Enter Description','height'=>'70px !important' ]); !!}' +
                    '</div>' +
                    '<div class="col-md-1"> <a class="btn btn-success add-row pull-right margin-top-23" href="#" ><i class="fa fa-plus-circle"></i> </a>' +
                    '</div>'+
                    '</div>';

                i++;

                $(this).parent().css("padding-top", '33px');
                // console.log(childrenDiv);
                childrenDiv.find('input').attr('readonly', true);
                childrenDiv.find('input').attr('required', true);
                childrenDiv.find('div:nth-child(8)').find('input').attr('required', false);
                childrenDiv.find('select').attr('readonly', true);
                childrenDiv.find('select').attr('required', true);
                var actionHtml = '<a href="#" class="btn-text-info margin-top-23 edit-button"><i class="fa fa-pencil"></i></a><a href="#" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a></span>';
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
                var actionHtml = '<a class="btn btn-warning pull-right margin-top-23" id="edit-complete" href="#" ><i class="fa fa-check-circle"></i> </a>';
                $(this).parent('div').html(actionHtml);
            });

            $(document).on('click','.delete-field', function(e) {
                e.preventDefault();

                var $div = $(this).parent().parent('.box-body');
                $div.remove();

                price_calculate();
            });

            $(document).on('click','#edit-complete', function() {

                var $div = $(this).parent().parent('.box-body');
                var childrenDiv = $div.children();
                $(this).parent().css("padding-top", '33px');
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

                price_calculate();
            });


            function price_calculate(){
                var total_bill = 0;
                for(var i = 1; i<=$('.purchase-return-div').length; i++) {

                    var purchaseDiv = $(".purchase-return-div:nth-child("+i+")");
                    var qty = purchaseDiv.find('div:nth-child(7)').find('input').val();
                    var price = purchaseDiv.find('div:nth-child(8)').find('input').val();
                    qty =  qty == undefined || qty == "" ? 0 : parseInt(qty);
                    price =  price == undefined || price == "" ? 0 : parseFloat(price);
                    var total_price = parseFloat(qty*price);
                    total_bill = total_bill+total_price;
                    purchaseDiv.find('div:nth-child(9)').find('input').val(total_price);
                }

                $('.return_total_value').val(total_bill);
            }


            $(document).on('click','.btn-at', function() {
                var $el = $(this);
                target = $el.data('option');
            });

            $(document).on('submit','#update',function(){

                var total_bill = parseFloat($("#total_bill").val());
                var return_total_value =  parseFloat($(".return_total_value").val());

                if(total_bill < return_total_value)
                {
                    bootbox.alert(" Return amount can\'t be bigger than Bill Amount");
                    return false;
                }else{
                    this.submit();
                }
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
                            $('#stock_'+id).val(data.stock);
                            $('#unit_'+id).val(data.unit);
                            $('#last_purchase_qty_'+id).val(data.supplier_purchases ? data.supplier_purchases.qty : 0);
                        }else{
//                        console.log(data);
                            bootbox.alert("No data Found!! ");
                        }
                    });
            });


        });

    </script>

@endpush


