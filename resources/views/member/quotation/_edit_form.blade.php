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
        <div class="row mx-0 mt-4">
            <div class="col-md-4 pl-1">
                <label for="suppliers"> REF </label>
                {!! Form::text('ref', null, ['class'=>'form-control','required', 'readonly']); !!}
            </div>
            <div class="col-md-2 pl-1">
                <label for="suppliers"> Date  <span class="text-red"> * </span></label>
                {!! Form::text('date', create_date_format($modal->quote_date,'/'),['id'=>'date', 'class'=>'form-control','required']); !!}
            </div>
            <div class="col-md-3 pl-1">
                <label for="suppliers"> Select Quote Company  <span class="text-red"> * </span></label>
                {!! Form::select('quotationer_id', $quote_companies , null,['id'=>'quotationer_id','class'=>'form-control select2','placeholder'=>'Please select  ']); !!}
            </div>
            <div class="col-md-3 pl-1">
                <label for="suppliers"> Select Attention Customer </label>
                {!! Form::select('quote_attention_id', $quote_attentions , null,['id'=>'quote_attention_id','class'=>'form-control select2','placeholder'=>'Please select  ']); !!}
            </div>
        </div>
        <div class="row mx-0">
            <div class="col-md-12 pl-1 mt-3">
                <label for="suppliers"> Subject  <span class="text-red"> * </span></label>
                {!! Form::text('subject', null,['id'=>'date', 'class'=>'form-control','required']); !!}
            </div>
            <div class="col-md-12 pl-1 mt-3">
                <label for="suppliers"> Message  <span class="text-red"> * </span></label>
                {!! Form::textarea('starting_text', null,['id'=>'date', 'class'=>'form-control','required']); !!}
            </div>

        </div>
    </div>

    <div class="box-footer pt-0">
        <div class="row mx-0 mt-4">
            <div class="col-md-4 pl-1">
                <label for="suppliers">Check Product </label>
                <select name="product" id="new_product" class='form-control select2'>
                    <option value=""> Please select </option>
                    @foreach($products as $value)
                        <option value="{{ $value->id }}" data-content="{{ $value->unit }}"> {{ $value->item_name }} </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 pl-1 pt-4">
                <a class="btn btn-primary mt-2 " id="add-in-list" href="javascript:void(0)" ><i class="fa fa-plus"></i> Add in List</a>
                <a class="btn btn-success mt-2" href="javascript:void(0)"  data-toggle="modal" data-target="#addProduct" id="create_product" ><i class="fa fa-plus"></i> New Product</a>
            </div>
        </div>

        <h5 class="text-bold  text-center pt-4"> Quotation Product</h5>
    </div>

</div>

<div class="box">
    <div class="box-body quotation-box">
        <div class="col-md-3 col-sm-12 text-left pl-0">
            <label for="product_id">Product Name </label>
        </div>

        <div class="col-md-1 col-sm-4 text-left pl-0">
            <label for="amount"> Unit </label>
        </div>
        <div class="col-md-1 col-sm-4 text-center">
            <label for="amount"> Qty </label>
        </div>
        <div class="col-md-2 px-0 col-sm-4 text-center">
            <label for="amount"> Price/Qty </label>
        </div>
        <div class="col-md-2 col-sm-4 text-center pl-0">
            <label for="amount"> Total Price </label>
        </div>
        <div class="col-md-2 col-sm-4 text-left pl-0">
            <label for="description">Description  </label>
        </div>
        <div class="col-md-1">

        </div>
    </div>
    @php $total = 0; @endphp
    @foreach( $modal->quotation_details as $key => $value)
    <div class="box-body quotation-box">
        <div class="col-md-3 pl-0">
            <input type="hidden" name="quotationDetails[]" value="{{ $value->id }}">
            {!! Form::hidden('product_id[]',$value->item_id,['id'=>'product_id_'.$key,'class'=>'form-control', 'required']); !!}
            {!! Form::text('product_name[]',$value->item->item_name,['id'=>'product_name_'.$key,'class'=>'form-control', 'required', 'readonly']); !!}
        </div>

        <div class="col-md-1 pl-0">
            {!! Form::text('unit[]',$value->unit,['id'=>'unit_'.$key,'class'=>'form-control', 'readonly']); !!}
        </div>
        <div class="col-md-1 px-0">
            {!! Form::number('qty[]',$value->qty,['id'=>'qty_'.$key,'class'=>'form-control input-number qty text-right', 'step'=>"any", 'required']); !!}
        </div>
        <div class="col-md-2">
            {!! Form::number('price[]',$value->price,['id'=>'price_'.$key,'class'=>'form-control input-number price text-right','placeholder'=>' Price', 'step'=>"any", 'required']); !!}
        </div>
        <div class="col-md-2 pl-0">
            {!! Form::number('total_price[]',$value->qty*$value->price,['id'=>'total_price_'.$key,'class'=>'form-control input-number text-right','placeholder'=>'Total Price', 'step'=>"any", 'required', 'readonly']); !!}
        </div>
        <div class="col-md-2 pl-0">
            {!! Form::text('description[]',$value->description,['class'=>'form-control ','placeholder'=>'Description']); !!}
        </div>

        {{--<div class="col-md-1" style=" padding-top:{{ $loop->last ?  "0px" : ($key==0 ? "30px" :"10px") }}">--}}
        <div class="col-md-1 text-center pl-0" style=" padding-top:10px">
{{--            @if($loop->last)--}}
            {{--<a class="btn btn-success add-row pull-right margin-top-3" href="javascript:void(0)" ><i class="fa fa-plus-circle"></i> </a>--}}
            {{--@else--}}
                {{--<a href="javascript:void(0)" class="btn-text-info margin-top-3  edit-button"><i class="fa fa-pencil"></i></a>--}}
            <a href="javascript:void(0)" class="btn-text-danger margin-top-3 delete-field"><i class="fa fa-close"></i></a>
            {{--@endif--}}
        </div>
    </div>
        @php
            $total += $value->total_price;
        @endphp
    @endforeach
</div>
<div class="col-md-12 px-0">
    <div class="">
        <div class="col-md-9 ">


                @foreach($quotation_terms as $key => $value)

                    @php
                        $sub = $value->subTermChild()->pluck('id')->toArray();
                        $selected = array_intersect($sub, $quote_terms);

                    @endphp
                <div class="row px-0">
                    <div class="col-md-3">
                        <h5 for="suppliers" @if($key==0) class="mt-5" @endif>  <input name="quote_term[]" type="checkbox" class="quote-term" {{ in_array($value->id, $quote_terms) ? 'checked' : '' }} value="{{$value->id}}" />  {{ $value->name }} </h5>
                    </div>
                    <div class="col-md-9 pl-1">
                       @if($key==0) <label for="suppliers"> Select Term Text </label>@endif
                        {!! Form::select('quote_sub_term[]', $value->subTermChild()->pluck('name', 'id') , $quote_terms,['id'=>'sub_term-'.$value->id, 'class'=>'form-control select2 quote_terms','placeholder'=>'Please select',   in_array($value->id, $quote_terms) ? '' : 'disabled'  ]); !!}
                    </div>
                </div>
                @endforeach

            <div class="form-group mt-3 border-top-1 mt-5 pt-5">
                <div class="form-group">
                    <label for="amount"> Select Contact Person  <span class="text-red"> * </span></label>
                    {!! Form::select('contact_quoting_id', $quoting , null,['id'=>'contact_quoting_id','class'=>'form-control select2','placeholder'=>'Please select']); !!}
                </div>
                <div class="form-group">
                    <label for="amount"> Select Quote By  <span class="text-red"> * </span></label>
                    {!! Form::select('quoting_id', $quoting , null,['id'=>'contact_quoting_id','class'=>'form-control select2','placeholder'=>'Please select']); !!}
                </div>
                <h5 for="suppliers" class="mt-5">  </h5>
                {!! Form::textarea('ending_text',null, ['id'=>'ending_text','class'=>'form-control','placeholder'=>'Enter notation']); !!}
            </div>

        </div>
        <div class="col-md-3">

            <div class="form-group">
                <label for="amount"> Total Price </label>
                {!! Form::number('total_bill',$total,['id'=>'total_bill','class'=>'form-control input-number','placeholder'=>'Enter Price', 'step'=>"any", 'required', 'readonly']); !!}
            </div>
            <div class="form-group">
                <label for="amount"> Discount </label>
                {!! Form::number('discount',0,['id'=>'discount','class'=>'form-control input-number','placeholder'=>'Enter Discount', 'step'=>"any"]); !!}
            </div>
            <div class="form-group">
                <label for="amount"> Grand Total </label>
                {!! Form::number('grand_total',null,['id'=>'grand_total','class'=>'form-control input-number','placeholder'=>'Enter total', 'step'=>"any", 'required', 'readonly']); !!}
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
            products = <?php print_r(json_encode($quotation_products)); ?>;

            var i = {{ count($modal->quotation_details) }};

            var productText = [];

            $(document).on('click', '#add-in-list', function () {

                var new_product = $("#new_product option:selected").val();
                var product_name = $("#new_product option:selected").text();
                var unit = $("#new_product option:selected").data('content');


                if (new_product == "") {
                    bootbox.alert("Please Select Item Name");
                    return false;
                }

                var optionName = '<option selected value="' + new_product + '" >' + product_name + '</option>';
                optionName = '<option value="">Select Item Name</option>' + optionName;

                if (productText.includes(new_product)) {
                    bootbox.alert("Already Product Selected");
                    return false;
                }

                productText.push(new_product);


                // $.each(products, function (key, value) {
                //     optionName = optionName + '<option value="' + key + '" >' + value + '</option>';
                // });

                var html = '<div class="box-body quotation-box">' +
                    '<div class="col-md-3 pl-0">' +
                    '<input type="hidden" name="quotationDetails[]" value="new"/><input required value="' + new_product + '" type="hidden"class="form-control"  name="product_id[]" readonly />' +
                    '<input required value="' + product_name + '" type="text" class="form-control"  name="product_name[]" readonly />' +
                    '</div> ' +
                    '<div class="col-md-1 pl-0">' +
                    '<input required id="unit_' + i + '" disabled  min="0" class="form-control" name="unit[]" type="text" value="'+unit+'">' +
                    '</div>' +
                    '<div class="col-md-1 px-0">' +
                    '<input required id="qty_' + i + '"  data-target="' + i + '" class="form-control text-right qty" min="0" name="qty[]" type="number">' +
                    '</div>' +
                    '<div class="col-md-2 ">' +
                    '<input required placeholder=" Price"  min="0"  id="price_' + i + '" data-option="' + i + '" class="form-control price text-right input-number" step="any" name="price[]"  type="number"/>' +
                    '</div>' +
                    '<div class="col-md-2 pl-0">' +
                    '<input required placeholder="Total Price" min="0"  id="price_' + i + '" data-option="' + i + '" class="form-control text-right input-number total_price" step="any" name="total_price[]"  type="number"/>' +
                    '</div>' +
                    '<div class="col-md-2 pl-0">' +
                    '<input placeholder="Total Description" class="form-control" name="description[]"  type="text"/>' +
                    '</div>' +
                    '<div style="padding-top:' + (i == 1 ? 10 : 10) + 'px" class="col-md-1 pl-0 text-center"><a href="javascript:void(0)" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a>' +
                    '</div>' +
                    '</div>';

                i++;

                // $(this).parent().css("padding-top", '30px');
                // var actionHtml = '<a href="javascript:void(0)" class="btn-text-info margin-top-3 edit-button"><i class="fa fa-pencil"></i></a><a href="javascript:void(0)" class="btn-text-danger margin-top-23 delete-field"><i class="fa fa-close"></i></a></span>';
                // $(".quotation-box").parent('div').html(actionHtml);

                $('input[type=number]').attr('min', 0);
                $(".quotation-box").parent('div').append(html);

            });

            $(document).on('click','.delete-field', function(e) {
                e.preventDefault();

                var $div = $(this).parent().parent('.box-body');
                $div.remove();

                total_price_calculate();
            });

            $(document).on('keyup','.qty', function(e) {
                e.preventDefault();
                var target = $(this).data('target');
                var qty = $(this).val();

            });

            $(document).on('keyup','#discount', function(e) {
                total_price_calculate();
            });

            $(document).on('keyup','.qty, .price', function(e) {
                e.preventDefault();
                total_price_calculate();
            });


            function price_calculate(){
                var total_bill = 0;
                for(var i = 2; i<=$('.quotation-box').length; i++) {

                    var qty = $('.quotation-box:nth-child(' + i + ') div:nth-child(3) input').val();
                    var price = $('.quotation-box:nth-child(' + i + ') div:nth-child(4) input').val();
                    price =  price == undefined || price == "" ? 0 : parseFloat(price);
                    var total_price = parseFloat(qty*price);
                    total_bill = total_bill+total_price;
                    $('.quotation-box:nth-child(' + i + ') div:nth-child(5) input').val(total_price);
                }
                $('#total_bill').val(total_bill);
            }


            function total_price_calculate()
            {

                price_calculate();

                var total_bill = $('#total_bill').val();
                var total_amount = parseFloat(total_bill);
                var discount = parseFloat($('#discount').val());
                var grand_total = (total_amount-discount).toFixed(2);

                $('#total_bill').val(total_amount);
                $('#discount').val(discount);
                $('#grand_total').val(grand_total);

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
                            $('#stock_'+id).val(data.stock);
                            $('#unit_'+id).val(data.unit);

                            $('#price_'+id).val(data.price != null ? data.price : 0);
                        }else{
                            bootbox.alert("No data Found!! ");
                        }
                    });
            });



            var quote_url = "{{ route('search.quote_attentions') }}";

            $(document).on('change', '#quotationer_id', function(e){
                e.preventDefault();

                var company_id = $(this).val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'company_id' : company_id
                };

                $.ajax({
                    type        : 'POST',
                    url         : quote_url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                })

                // using the done promise callback
                    .done(function(data) {

                        if(data.status == "success")
                        {
                            var html = "<option value=''>Please Select </option>";

                            $.each( data.quote_attentions, function( key, value ) {
                                html += "<option value='"+value.id+"'>"+value.info_details+"</option>";
                            });

                            $("#quote_attention_id").html(html);
                            $('#quote_attention_id').select2();
                        }else {

                            var html = "<option value=''>Please Select </option>";
                            $("#quote_attention_id").html(html);
                            $('#quote_attention_id').select2();
                        }
                    });
            });


            $(".quote-term").change( function () {

                if($(this).is(':checked') == true)
                {
                    $("#sub_term-"+$(this).val()).attr('required', true);
                    $("#sub_term-"+$(this).val()).attr('disabled', false);
                }else{
                    $("#sub_term-"+$(this).val()).attr('required', false);
                    $("#sub_term-"+$(this).val()).attr('disabled', true);
                }

            });

        });

    </script>

@endpush

