<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

$route = \Auth::user()->can(['member.items.index']) ? route('member.items.index') : "#";
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Products',
        'href' => $route,
    ],
    [
        'name' => 'Update ',
    ],
];

$data['data'] = [
    'name' => 'Products',
    'title' => 'Update  Product',
    'heading' => trans('common.update_product'),
];

?>
@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">


    <style type="text/css">
        div.file {
            position: relative;
            overflow: hidden;
        }
        .file input {
            position: absolute;
            font-size: 50px;
            opacity: 0;
            right: 0;
            top: 0;
        }
    </style>
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
                    <h3 class="box-title">{{__('common.update_product')}}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                {!! Form::model($modal, ['route' => ['member.variant-items.update', $modal],  'method' => 'put', 'role'=>'form', 'files'=>true, 'id' => "product-store"]) !!}


                <div class="box-body">


                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="item_name">{{__('common.item_name')}} <span class="text-red"> * </span> </label>
                            {!! Form::text('item_name', optional($modal->main_product)->name, [
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
                            <label for="status">{{__('common.unit')}} <span class="text-red"> * </span> </label>
                            {!! Form::select('unit', $units, null, [
                                'id' => 'unit',
                                'class' => 'form-control  select2',
                                'placeholder' => trans('common.please_select_unit'),
                                'required',
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
                            <label for="warranty"> {{__('common.warranty')}} ({{__('common.total_month')}}) </label>
                            {!! Form::number('warranty', null, [
                                'id' => 'warranty',
                                'class' => 'form-control',
                                'placeholder' => trans('common.enter_warranty'),
                            ]) !!}
                        </div>

                    </div>

                    <div class="col-md-4">

                        @php
                            $var = $modal->productCode;

                            // Find the position of the hyphen
                            $hyphenPosition = strpos($var, '-');

                            // Check if the hyphen is found
                            if ($hyphenPosition !== false) {
                                // Extract the part of the string before the hyphen
                                $var = substr($var, 0, $hyphenPosition);
                            }
                        @endphp

                        <div class="form-group">
                            <label for="item_name">{{__('common.product_code')}} <span class="text-red"> * </span> </label>
                            {!! Form::text('productCode', $var, [
                                'id' => 'product_code',
                                'class' => 'form-control',
                                'placeholder' => trans('common.enter_product_code'),
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
                            <label for="warranty"> {{__('common.guarantee')}} ({{__('common.total_month')}}) </label>
                            {!! Form::number('guarantee', null, [
                                'id' => 'guarantee',
                                'class' => 'form-control',
                                'placeholder' => trans('common.enter_guarantee'),
                            ]) !!}
                        </div>

                        <div class="form-group">
                            <label for="status">{{__('common.status')}} </label>
                            {!! Form::select('status', ['active' =>trans('common.active'), 'inactive' => trans('common.inactive')], null, [
                                'id' => 'status',
                                'class' => 'form-control',
                                'required',
                            ]) !!}
                        </div>
                    </div>

                    <div class="col-md-4">

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
                            <label for="description">{{__('common.description')}} </label>
                            {!! Form::textarea('description', null, [
                                'id' => 'description',
                                'rows' => '7',
                                'class' => 'form-control',
                                'placeholder' => trans('common.enter_description'), 'style'=>"height:105px"
                            ]) !!}
                        </div>

{{--                        <div class="form-group">--}}
{{--                            <label for="product_image">Product Image (Image must be JPG) </label>--}}
{{--                            --}}{{----}}
{{--                                    {!! Form::file('product_image', null, [--}}
{{--                                        'id' => 'product_image',--}}
{{--                                        'accept' => 'image/*',--}}
{{--                                        'class' => 'form-control',--}}
{{--                                        'placeholder' => 'Enter image',--}}
{{--                                    ]) !!} --}}

{{--                            <input type="file" id='product_image' class="form-control" accept="image/jpeg"--}}
{{--                                   name="product_image"--}}
{{--                                   placeholder="Import image" onchange="getImagePreview(this)">--}}
{{--                            <input type="hidden" id="front-image-url" value="">--}}
{{--                            <div class="py-1" id="front-image-preview" style="display: flex; gap: 10px;flex-wrap: wrap">--}}
{{--                            </div>--}}

{{--                        </div>--}}
                    </div>

                    <div class="col-md-12">
                        <h4 class="border-bottom-1 pb-3"> {{__('common.select_variants')}}</h4>

                        <div class="form-group col-md-4">
                            <label for="status">{{__('common.select_multi_variants')}} <span class="text-red"> * </span> </label>
                            {!! Form::select('variant_id[]', $variants, $variant_ids, [
                                'id' => 'variant_id',
                                'class' => 'form-control select2',
                                'required', 'multiple', 'disabled'
                            ]) !!}
                        </div>

                        @if (config('settings.warehouse'))
                        <div class="form-group col-md-4">
                            <label for="status">{{__('common.select_warehouses')}} <span class="text-red"> * </span> </label>
                            {!! Form::select('warehouse_id[]', $warehouses, $initial_warehouses, [
                              'class' => 'form-control select2', 'disabled',
                              'required', 'multiple', 'id' => 'warehouse_id'
                           ]) !!}
                        </div>
                        @endif


                    </div>

                    <div class="col-md-12" id="variant_html">
                        @foreach($variantData as $variant_values)
                            @include('member.items.variants.edit_variant_values', compact('variant_values'))
                        @endforeach
                    </div>

                    <div class="col-md-12" id="variant_product">
                            @include('member.items.variants.update_variant_product', compact('variant_products'))
                    </div>



                    <div class="box-footer" >
                        <div class="col-md-12">
                            <button type="button" id="generate-stock" class="hidden btn btn-success pull-right"> {{__('common.generate_initial_stock')}}</button>

                            <button type="button" id="load-submit" class="  btn btn-primary">{{__('common.submit')}}</button>
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

    <!-- CK Editor -->
    <script src="{{ asset('public/memberLTE/bower_components/ckeditor/ckeditor.js') }}"></script>

    <script>
        $(function () {
            // Replace the <textarea id="editor1"> with a CKEditor
            // instance, using default configuration.
            CKEDITOR.replace('description');
        })
    </script>

    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">
        $(function () {

            $('.select2').select2()

            $('.initial_date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });

            var $warehouses = [];

            $(document).on('change', '#variant_id', function (e) {
                e.preventDefault();
                var url = "{{ route('search.variant') }}";
                var id = $(this).val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'id' : id
                };
                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function(data) {
                    // console.log(data);
                    if(data.status == "success")
                    {
                        var html = data.variant_html;

                        $('#variant_html').html(html);
                        $('#variant_product').html('');
                        $('#load-submit').addClass("hidden");
                        $("#generate-stock").removeClass("hidden");
                        $('.select2').select2();

                    }else{
//                        console.log(data);
                        bootbox.alert("No data Found!! ");
                    }
                });
            });

            $(document).on('click', '#generate-stock', function (e) {
                e.preventDefault();
                var url = "{{ route('generate.variant_product') }}";
                var variant_id = $("#variant_id").val();
                var warehouse_id = $("#warehouse_id").val();

                var variantValues = [];

                for ($i=0; $i<variant_id.length; $i++)
                {
                    var variantId = variant_id[$i];
                    variantValues[variantId] = $("#variant_value_"+variantId).val();
                }

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'variant_id' : variant_id,
                    'variantValues' : variantValues,
                    'warehouse_id' : warehouse_id
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
                        var html = data.variant_product;

                        $('#variant_product').html(html);
                        $('#load-submit').removeClass("hidden");
                        $("#generate-stock").addClass("hidden");


                        $('.initial_date').datepicker({
                            "setDate": new Date(),
                            "format": 'mm/dd/yyyy',
                            "endDate": "+0d",
                            "todayHighlight": true,
                            "autoclose": true
                        });
                    }else{
//                        console.log(data);
                        bootbox.alert("No data Found!! ");
                    }
                });
            });


            $(document).on('click', '.delete-row', function () {

                var $this = $(this);

                bootbox.confirm("Are you sure to delete this Row?",
                    function (result) {

                        if (result == true) {
                            $this.parent().parent().remove();
                        }
                    });

            });



            $(document).on('click', '#load-submit', function () {

                var isValid = true;

                // Check all required text and password inputs
                $("#product-store input[type='text'], #product-store input[type='number']").each(function() {
                    if ($(this).prop("required") && $(this).val().trim() === "") {
                        $(this).css("border","1px solid red")
                        isValid = false;
                        return false; // Break the loop if one required field is empty
                    }
                });

                if (!isValid) {
                    bootbox.alert("All required fields must be filled out");
                } else {
                    // Perform any other actions or submit the form
                    $("#product-store").submit();
                }

            });



        });
    </script>
@endpush

