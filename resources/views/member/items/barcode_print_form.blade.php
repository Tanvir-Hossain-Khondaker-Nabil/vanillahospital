<?php
/**
 * Print Barcoded by PhpStorm.
 * User: Mobarok Hossen
 * Date: 8/29/2019
 * Time: 1:15 PM
 */


 $route =  \Auth::user()->can(['member.items.index']) ? route('member.items.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Items/Products',
        'href' => $route,
    ],
    [
        'name' => 'Print Barcode',
    ],
];

$data['data'] = [
    'name' => 'Products',
    'title'=>'Print Barcode Product',
    'heading' => trans('common.print_barcode_product'),
];

?>
@extends('layouts.back-end.master', $data)
@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
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
                    <h3 class="box-title">{{__('common.print_barcode_product')}}</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->

                <div class="box-body">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="item_name">{{__('common.product_name')}} <span class="text-red"> * </span> </label>
                            {!! Form::select('item_id', $products, null,['id'=>'item_id', 'class'=>'form-control select2','required', 'placeholder'=>trans('common.select_item_name')]); !!}
                        </div>
                        <div class="form-group">
                            <label for="warranty"> {{__("common.print_qty")}}  <span class="text-red"> * </span> </label>
                            {!! Form::text('print_qty',null,['id'=>'print_qty','class'=>'form-control input-number','placeholder'=>trans('common.enter_print_quantity'), 'required']); !!}
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="col-md-12">
                            <a href="#" class=" btn btn-sm btn-primary hidden" id="btn-print"> <i class="fa fa-print"></i> {{__("common.submit")}} </a>
                            <a href="#" class=" btn btn-sm btn-primary" id="print-barcode"> <i class="fa fa-print"></i> {{__("common.submit")}} </a>
                        </div>
                    </div>

                </div>
            <!-- /.box -->
            </div>
        </div>
    </div>
@endsection


@push('scripts')
   <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Date range picker -->
    <script type="text/javascript">

        // var date = new Date();
        $(function () {
            $('.select2').select2();

            $('#print-barcode').click( function (e) {
               var item_id = $("#item_id").val();
               var print_qty = $("#print_qty").val();
               if( item_id == '' || print_qty == '') {
                   if(item_id == "")
                   {
                       bootbox.alert("Please select product Name");
                   }else{
                       bootbox.alert("Please select print Qty");
                   }
                   return false;
               }

               var link = "{{ route('member.items.print_barcode') }}"+"?item_id="+item_id+"&print_qty="+print_qty;
                $("#btn-print").attr("href", link);
                $("#btn-print").trigger('click');
                // $("#print-barcode").attr("href", link);
                // $("#print-barcode").trigger('click');
            });
        });
        </script>
@endpush
