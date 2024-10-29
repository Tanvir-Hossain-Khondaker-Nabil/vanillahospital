<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 4:25 PM
 */
?>

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush


<div class="col-md-6">
    <div class="form-group">
        <label for="item_name">{{__('common.item_name')}} <span class="text-red"> * </span> </label>
        {!! Form::text('item_name', null, [
            'id' => 'item_name',
            'class' => 'form-control',
            'placeholder' => trans('common.enter_item_name'),
            'required',
        ]) !!}
    </div>
{{--     <div class="form-group">
        <label for="product_type_id">Product Type</label>
        {!! Form::select('product_type_id', $product_types, null, [
            'id' => 'product_type_id',
            'class' => 'form-control select2',
            'placeholder' => 'Please select',
        ]) !!}
    </div> --}}

    <div class="form-group">
        <label for="item_name"> {{__('common.product_code')}} <span class="text-red"> * </span> </label>
        {!! Form::text('productCode', null, [
            'id' => 'product_code',
            'class' => 'form-control',
            'placeholder' => trans('common.enter_product_code'),
            'required',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="item_name">{{__('common.sku_code')}} <span class="text-red"> </span> </label>
        {!! Form::text('skuCode', null, [
            'id' => 'sku_code',
            'class' => 'form-control',
            'placeholder' => trans('common.enter_sku_code'),
        ]) !!}
    </div>
    <div class="form-group">
        <label for="status">{{__('common.product_category')}} <span class="text-red"> * </span> </label>
        {!! Form::select('category_id', $categories, null, [
            'id' => 'category_id',
            'class' => 'form-control select2',
            'placeholder' => trans('common.please_select'),
            'required',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="status">{{__('common.brand')}} </label>
        {!! Form::select('brand_id', $brands, null, [
            'id' => 'brand_id',
            'class' => 'form-control select2',
            'placeholder' => trans('common.please_select'),
        ]) !!}
    </div>
    <div class="form-group">
        <label for="product_image">{{__("common.product_image")}} ({{__('common.image_must_be')}} JPG) </label>
{{--
        {!! Form::file('product_image', null, [
            'id' => 'product_image',
            'accept' => 'image/*',
            'class' => 'form-control',
            'placeholder' => 'Enter image',
        ]) !!} --}}

        <input type="file" id='product_image' class="form-control" accept="image/*" name="product_image"
            placeholder="Import image" onchange="getImagePreview(this)">
        <input type="hidden" id="front-image-url" value="">
        <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">
        </div>

    </div>
    <div class="form-group">
        <label for="status">{{__('common.unit')}} <span class="text-red"> * </span> </label>
        {!! Form::select('unit', $units, null, [
            'id' => 'unit',
            'class' => 'form-control',
            'placeholder' => trans('common.please_select'),
            'required',
        ]) !!}
    </div>


    <div class="form-group">
        <label for="warranty"> {{__('common.opening_stock')}} </label>
        {!! Form::number('stock', null, [
            'id' => 'stock',
            'class' => 'form-control',
            'placeholder' => trans('common.enter_initial_stock'),
            'required',
            'step' => 'any',
        ]) !!}
    </div>


    @if (config('settings.warehouse'))

        <div class="form-group">
            <table width="100%">
                <thead>
                    <tr>
                        <th width="350px"> {{__('common.warehouse')}}</th>
                        <th width="150px" class="px-3">{{__('common.qty')}}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="warehouse">
                    @if (isset($modal) && count($modal->warehouses) > 0)
                        @foreach ($modal->warehouses as $key => $value)
                            <tr>
                                <td class="pt-2"> {!! Form::select('warehouse_id[]', $warehouses, $value->warehouse_id, [
                                    'class' => 'form-control select2',
                                    'required',
                                    'placeholder' => trans('common.select_warehouse'),
                                ]) !!}</td>
                                <td class="pt-2 px-3">{!! Form::number('unload_qty[]', $value->qty, [
                                    'class' => 'form-control  text-center',
                                    'step' => 'any',
                                    'required',
                                ]) !!}</td>
                                <td class="pt-2">
                                    @if ($key == 0)
                                        <a class="btn btn-primary btn-sm multi-row" href="javascript:void(0)"
                                            data-content="0"> <i class="fa fa-clone"></i> </a>
                                    @else
                                        <a class="btn btn-danger delete-row btn-sm" href="javascript:void(0)"> <i
                                                class="fa fa-trash"></i> </a>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td> {!! Form::select('warehouse_id[]', $warehouses, null, [
                                'class' => 'form-control select2',
                                'required',
                                'placeholder' => trans('common.select_warehouse'),
                            ]) !!}</td>
                            <td class="px-3">{!! Form::number('unload_qty[]', null, ['class' => 'form-control  text-center', 'step' => 'any', 'required']) !!}</td>
                            <td><a class="btn btn-primary btn-sm multi-row" href="javascript:void(0)" data-content="0">
                                    <i class="fa fa-clone"></i> </a></td>
                        </tr>
                    @endif
                </tbody>

            </table>
        </div>

    @endif

    <div class="form-group">
        <label for="initial_balance">{{__('common.opening_stock_date')}} </label>
        {!! Form::text('initial_date', null, [
            'id' => 'date',
            'class' => 'form-control initial_date',
            'placeholder' => trans('common.initial_stock_date'),
            'autocomplete' => 'off',
        ]) !!}
    </div>

    <div class="form-group">
        <label for="warranty"> {{__('common.selling_price')}} <span class="text-red"> * </span> </label>
        {!! Form::number('price', null, [
            'id' => 'price',
            'class' => 'form-control',
            'placeholder' => trans('common.enter_price'),
            'required',
            'step' => 'any',
        ]) !!}
    </div>
</div>
<div class="col-md-6">
    <div class="form-group">
        <label for="description">{{__('common.description')}} </label>
        {!! Form::textarea('description', null, [
            'id' => 'description',
            'rows' => '6',
            'class' => 'form-control',
            'placeholder' => trans('common.enter_description'),
        ]) !!}
    </div>
    <div class="form-group">
        <label for="warranty"> {{__('common.warranty')}} ({{__('common.total_month')}}) </label>
        {!! Form::number('warranty', null, [
            'id' => 'warranty',
            'class' => 'form-control',
            'placeholder' => trans('common.enter_warranty'),
        ]) !!}
    </div>
    <div class="form-group">
        <label for="warranty"> {{__('common.guarantee')}} ({{__('common.total_month')}}) </label>
        {!! Form::number('guarantee', null, [
            'id' => 'guarantee',
            'class' => 'form-control',
            'placeholder' => trans('common.enter_guarantee'),
        ]) !!}
    </div>
    <div class="form-group">
        <label for="status">{{__('common.is_service_product')}} </label>
        {!! Form::select('is_service', [0 => trans('common.no'), 1 => trans('common.yes')], null, ['id' => 'is_service', 'class' => 'form-control']) !!}
    </div>
    <div class="form-group">
        <label for="status">{{__('common.is_repair_product')}} </label>
        {!! Form::select('is_repair_item', [0 => trans('common.no'), 1 => trans('common.yes')], null, ['id' => 'is_repair_item', 'class' => 'form-control']) !!}
    </div>

    <div class="form-group">
        <label for="warranty"> {{__("common.purchase_price")}} </label>
        {!! Form::number('purchase_price', null, [
            'id' => 'purchase_price',
            'class' => 'form-control',
            'placeholder' => trans('common.enter_purchase_price'),
            'step' => 'any',
        ]) !!}
    </div>
    <div class="form-group">
        <label for="status">{{__('common.status')}} </label>
        {!! Form::select('status', ['active' => trans('common.active'), 'inactive' => trans('common.inactive')], null, [
            'id' => 'status',
            'class' => 'form-control',
            'required',
        ]) !!}
    </div>
</div>



@push('scripts')
    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- CK Editor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>


    <script type="text/javascript">
        $(function() {

            $('.select2').select2()

            $('#date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });


            var $warehouses = [];



            $(document).on('click', '.delete-row', function () {

                var $this = $(this);

                bootbox.confirm("{{__('common.are_you_sure_to_delete_this_row')}}",
                    function(result) {

                        if(result==true)
                        {
                            $this.parent().parent().remove();
                        }
                    });

            });


            @if (config('settings.warehouse'))

                $warehouses = <?php print_r(json_encode($warehouses)); ?>;

                $(document).on('click', '.multi-row', function() {
                    var $content = $(this).data('content');

                    var load_qty = $("#stock").val();
                    var total_qty = checkLoadQty($content);

                    if (load_qty > total_qty) {
                        var optionName = '<option selected="selected" value="">{{__("common.select_warehouse")}}</option>';

                        $.each($warehouses, function(key, value) {

                            if ($.inArray(key, warehouseSelected) >= 0) {} else {
                                optionName = optionName + '<option value="' + key + '" >' + value +
                                    '</option>';
                            }

                        });

                        var html =
                            '<tr><td  class="pt-2"><select class="form-control select2" required  name="warehouse_id[]">' +
                            optionName +
                            '</select></td><td  class="pt-2 px-3"><input type="number"  required class="form-control text-center" name="unload_qty[]" type="number" /></td><td  class="pt-2"><a class="btn btn-danger delete-row btn-sm" href="javascript:void(0)"> <i class="fa fa-trash"></i> </a></td></tr>';

                        $("#warehouse").append(html);

                        $('.select2').select2();
                    } else {
                        bootbox.alert("{{__('common.already_warehouse_load_qty_assigned')}}");
                    }
                });


                function checkLoadQty($target) {
                    var allTr = $("#warehouse").find("tr");
                    var totalQty = 0;
                    warehouseSelected = [];

                    for (var $i = 1; $i <= allTr.length; $i++) {
                        var warehouseId = $("#warehouse")
                            .find("tr:nth-child(" + $i + ") td:first-child select :selected").val();
                        warehouseSelected.push(warehouseId);

                        var qty = $("#warehouse")
                            .find("tr:nth-child(" + $i + ") td:nth-child(2) input").val();

                        totalQty = parseFloat(totalQty) + parseFloat(qty);
                    }

                    console.log(warehouseSelected);

                    return totalQty;
                }


                var warehouseSelected = [];
                $(document).on('click', '#load-submit', function() {

                    var $checkSubmit = [];
                    var $warehouseSelected = [];
                    var $warehouseSelectedRequired = [];
                    var $loadQty = parseFloat($("#stock").val());

                    var $i = 0;
                    var allTr = $("#warehouse").find("tr");

                    var totalItemQty = 0;
                    for (var $j = 1; $j <= allTr.length; $j++) {
                        var warehouseId = $("#warehouse")
                            .find("tr:nth-child(" + $j + ") td:first-child select :selected").val();
                        $warehouseSelected.push(warehouseId);

                        if (warehouseId == "") {
                            $warehouseSelectedRequired.push($j);
                            $("#warehouse")
                                .find("tr:nth-child(" + $j + ") td:first-child select").css("border",
                                    '1px solid red')
                        }


                        var qty = $("#warehouse")
                            .find("tr:nth-child(" + $j + ") td:nth-child(2) input").val();

                        if (qty == "") {
                            qty = 0;
                            $("#warehouse")
                                .find("tr:nth-child(" + $j + ") td:nth-child(2) input").css("border",
                                    '1px solid red')
                        }

                        totalItemQty = parseFloat(totalItemQty) + parseFloat(qty);
                    }

                    for (var $i = 0; $i < $warehouseSelectedRequired.length; $i++) {
                        $("#warehouse").find("tr:nth-child(" + $warehouseSelectedRequired[$i] +
                            ") td:first-child select").css("border", '1px solid red');
                    }

                    if ($loadQty == totalItemQty) {
                        if ($warehouseSelected.includes("") === false) {
                            $("#product-store").submit();
                        }
                    }


                });
            @else

                $(document).on('click', '#load-submit', function() {
                    $('#product-store').submit();
                });
            @endif



        });
    </script>
@endpush
