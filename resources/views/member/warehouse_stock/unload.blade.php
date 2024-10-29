<?php
/**
 * Editd by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/26/2019
 * Time: 2:35 PM
 */

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('member.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'warehouse',
        'href' => route('member.warehouse.index'),
    ],
    [
        'name' => 'unload',
    ],
];

$data['data'] = [
    'name' => 'Unload from Warehouse',
    'title'=> 'Unload from Warehouse',
    'heading' => trans('common.unload_from_warehouse'),
];

?>
@extends('layouts.back-end.master', $data)


@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush

@section('contents')

    <div class=" box box-default">

        @include('common._alert')

        <div class="box-header with-border">
            <h3 class="box-title">{{__('common.invoice_no')}}: {{'INV-'.$model->id}}</h3>
        </div>

        {!! Form::model($model, ['route' => ['member.warehouse.unload.store'],  'method' => 'post', 'role'=>'form', 'id'=>'sale_unload']) !!}

        <input type="hidden" value="{{ $model->id }}" name="sale_id">

        <div class="box-body pb-0">
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputPassword" >{{__('common.sale_date')}}</label>
                        {!! Form::text('date',create_date_format($model->date, '/'),['id'=>'date','class'=>'form-control']); !!}
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group ">
                        <label for="inputPassword" >{{__('common.customer_name')}}</label>

                        {!! Form::text('name',$model->customer ? $model->customer->name : "Cash Customer",['class'=>'form-control','readonly']); !!}
                    </div>
                </div>



            </div>

        </div>

        <!-- /.row -->
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-sx-12  new-table-responsive text-center">

                <h4>{{__('common.unload_itemwise_unload_bulk')}} <label class="btn btn-bitbucket"> {{__('common.unload')}}   <i class="fa fa-minus-circle"></i> </label>  </h4>

                <input type="hidden" id="total_products" value="{{count($model->sale_details)}}">
                <table class="table table-responsive table-striped">

                    @php
                        $i=0;
                    @endphp
                    @foreach($model->sale_details as $key => $sale)
                        @php
                            $store_qty = $sale->warehouseItemQty($sale->id, $sale->item_id);
                            $warehouse = $sale->warehouse($sale->id, $sale->item_id);

                        $qty = $store_qty>0? "Unload: ".$store_qty.",Due: "  : "";
                        $qty .= ($sale->qty-$store_qty);

                        @endphp

                        @if($store_qty<$sale->qty)


                            @if($key==0)
                                <thead>
                                <tr>
                                    <th  width="300px">{{__('common.product')}}</th>
                                    <th   width="80px"  class="text-center">{{__("common.unit")}}</th>
                                    <th   class="text-center">{{__('common.last_unload_qty')}}</th>
                                    <th    class="text-center">{{__('common.qty')}}</th>

                                    <th width="500px" >
                                        <table  width="100%" >
                                            <tr>
                                                <th  width="250px"  class="text-left">{{__('common.warehouse')}}</th>
                                                <th   width="125px" class="text-left">{{__('common.stock_qty')}}</th>
                                                <th  width="125px"   class="text-left">{{__('common.unload_qty')}}</th>
                                            </tr>
                                        </table>
                                    </th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>

                                @endif
                                @php
                                    $i++;
                                @endphp
                                <tr class="item-row">
                                    <td class="item-name" width="300px">
                                        {!! Form::hidden('sale_details[]', $sale->id,['class'=>'form-control']); !!}
                                        {!! Form::hidden('product_id[]', $sale->item_id,['class'=>'form-control']); !!}
                                        {!! Form::text('product[]',$sale->item->item_name,['class'=>'form-control', 'disabled']); !!}
                                    </td>
                                    <td width="80px">
                                        {!! Form::text('unit[]',$sale->unit,['class'=>'form-control text-center', 'disabled']); !!}
                                    </td>
                                    <td>{!! Form::text('last_load_qty[]',$sale->warehouseLastUnload($sale->item_id),['class'=>'form-control text-center', 'readonly']); !!}</td>
                                    <td>{!! Form::text('qty[]',$qty,['class'=>'form-control text-center ',  'step'=>"any", 'required', 'readonly']); !!}
                                        {!! Form::hidden('u_qty[]',$sale->qty-$store_qty,['id'=>'qty_'.$key,'class'=>'form-control text-center ',  'step'=>"any", 'required', 'readonly']); !!}
                                    </td>
                                    <td class="item-name" width="500px">
                                        <table width="100%" id="warehouse_{{$key}}">
                                            <tr>
                                                <td width="250px"> {!! Form::select('warehouse_id['.$sale->item_id.'][]', $warehouses, null,[ 'class'=>'form-control select2 warehouse-product','required', 'placeholder'=>'Select Warehouse',  'data-target'=>$sale->item_id.$key, 'data-item'=>$sale->item_id ]); !!}</td>
                                                <td width="125px" class="px-3">{!! Form::text('available_qty[]',null,['class'=>'form-control text-center', 'disabled', 'id'=>"available_qty_".$sale->item_id.$key ]); !!}</td>
                                                <td width="125px" class="px-3">{!! Form::number('unload_qty['.$sale->item_id.'][]',$sale->qty-$store_qty,['class'=>'form-control  text-center',  'step'=>"any", 'required', 'max'=>$sale->qty-$store_qty]); !!}</td>
                                                <td><a class="btn btn-primary btn-sm multi-row" href="javascript:void(0)" data-target="{{$sale->item_id}}" data-content="{{$key}}"> <i class="fa fa-clone"></i> </a></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @else
                                    @if($key==0)
                                        <thead>
                                        <tr>
                                            <th  width="300px">{{__('common.product')}}</th>
                                            <th   width="80px"  class="text-center">{{__('common.unit')}}</th>
                                            <th   class="text-center">{{__('common.last_unload_qty')}}</th>
                                            <th    class="text-center">{{__('common.qtt')}}</th>

                                            <th width="350px" >
                                                <table  width="100%" >
                                                    <tr>
                                                        <th  width="250px"  class="text-left">{{__('common.warehouse')}}</th>
                                                        <th  width="125px"   class="text-left">{{__('common.unload_qty')}}</th>
                                                    </tr>
                                                </table>
                                            </th>
                                            <th></th>
                                        </tr>
                                        </thead>
                                <tbody>

                                @endif

                                <tr class="item-row">
                                    <td class="text-left" width="300px">
                                        {{ $sale->item->item_name }}
                                    </td>
                                    <td   class="text-center">
                                        {{ $sale->unit }}
                                    </td>
                                    <td   class="text-center">
                                        {{ $store_qty }}
                                    </td>
                                    <td    class="text-center">
                                        {{__('common.unloaded')}}
                                    </td>

                                    <td width="350px" >
                                        <table  width="100%" >

                                            @foreach($warehouse as $key1 => $house)
                                                <tr style="border-bottom: 0.5px solid #919191;">
                                                    <td  width="250px"  style="height: 25px; vertical-align: {{ ($key1==0)?'top':'middle' }};" class="text-left">{{ $house->warehouse->title }}</td>
                                                    <td  width="125px"  style="height: 25px; vertical-align: {{ ($key1==0)?'top':'middle' }};" class="text-left"> {{ $house->qty }}</td>
                                                </tr>
                                            @endforeach

                                        </table>
                                    </td>
                                    <td >

                                    </td>
                                </tr>
                                @endif

                                @endforeach
                                </tbody>
                </table>

            </div>

        </div>

        @if($i>0)

            <div style="margin-top: 20px; " class="row">

                <div class="col-lg-12 col-md-12 "   style="max-width: 100%; flex: 100%;">
                    <table class="new-table-3 pull-right" style="margin-top: 20px; margin-bottom: 20px"  >
                        <tr>
                            <td>
                                <button style="width: 150px !important; margin-right: 30px" type="button" class="btn btn-block btn-primary unload-submit">{{__('common.complete_unload')}} </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

        @else
            <div style="margin-top: 20px; " class="row">

                <div class="col-lg-12 col-md-12 "   style="max-width: 100%; ">
                    <div class="alert alert-success text-center">
                        <h4 class="mb-0">  {{__('common.unload_completed')}}</h4>
                    </div>
                </div>
            </div>

        @endif


        {!! Form::close() !!}


    </div>



@endsection


@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Date range picker -->
    <script type="text/javascript">

        $(function () {

            $('#date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });

            $('.select2').select2();


            var $warehouses = [];
            $warehouses = <?php print_r(json_encode($warehouses)); ?>;

            var warehouseSelected = [];


            $(document).on('click', '.unload-submit', function () {
                var $total_products =  $("#total_products").val();

                var $checkSubmit  = [];
                var $warehouseSelected  = [];
                var $warehouseSelectedRequired  = [];
                for (var $i=0; $i<$total_products; $i++)
                {
                    var $loadQty = parseFloat($("#qty_"+$i).val());


                    var allTr =  $("#warehouse_"+$i).find("tr");


                    var totalItemQty = 0 ;
                    for (var $j=1; $j<=allTr.length; $j++)
                    {
                        var warehouseId = $("#warehouse_"+$i)
                            .find("tr:nth-child("+$j+") td:first-child select :selected").val();
                        $warehouseSelected.push(warehouseId);

                        if(warehouseId == "")
                            $warehouseSelectedRequired.push($i);


                        var qty = $("#warehouse_"+$i)
                            .find("tr:nth-child("+$j+") td:nth-child(3) input").val();

                        totalItemQty = parseFloat(totalItemQty)+parseFloat(qty);
                    }

                    if($loadQty != totalItemQty)
                    {
                        $checkSubmit.push($i);
                    }

                }

                console.log($checkSubmit);
                console.log("--------------------------");
                console.log($warehouseSelected);


                if($checkSubmit.length>0)
                {
                    for (var $i=0; $i<$total_products; $i++)
                    {
                        $("#warehouse_"+$i).find("tr td:first-child").css("border", 'none')
                        $("#warehouse_"+$i).find("tr td:nth-child(3) input").css("border", '2px solid #d2d6de')
                    }

                    for (var $i=0; $i<$checkSubmit.length; $i++)
                    {
                        $("#warehouse_"+$checkSubmit[$i]).find("tr td:nth-child(3)  input").css("border", '1px solid red')
                    }


                }else{

                    if($warehouseSelected.includes("") === false)
                        $("#sale_unload").submit();
                    else
                    {

                        for (var $i=0; $i<$total_products; $i++)
                        {
                            $("#warehouse_"+$i).find("tr td:first-child").css("border", 'none')
                            $("#warehouse_"+$i).find("tr td:nth-child(3)  input").css("border", '2px solid #d2d6de')
                        }

                        for (var $i=0; $i<$warehouseSelectedRequired.length; $i++)
                        {
                            $("#warehouse_"+$warehouseSelectedRequired[$i]).find("tr td:first-child").css("border", '1px solid red')
                        }
                        bootbox.alert("{{__('common.please_complete_you_warehouse_selection')}}");
                    }
                }


            });


            $(document).on('click', '.delete-row', function () {

                bootbox.confirm("{{__('common.are_you_sure_to_delete_this_row')}}",
                    function(result) {
                        if(result==true)
                        {
                            $(".delete-row").parent().parent().remove();
                        }
                    });

            });

            $(document).on('click', '.multi-row', function () {
                var $target = $(this).data('target');
                var $content = $(this).data('content');

                var load_qty =  $("#qty_"+$content).val();
                var total_qty = checkLoadQty($content);


                if(load_qty > total_qty)
                {
                    var optionName='<option selected="selected" value="">{{__("common.select_warehouse")}}</option>';

                    $.each( $warehouses, function( key, value ) {

                        if($.inArray(key, warehouseSelected[$content])>=0)
                        {}else{
                            optionName = optionName+'<option value="'+key+'" >'+value+'</option>';
                        }

                    });

                    var avaTr =  $target+""+$("#warehouse_"+$content).find("tr").length;

                    var html =  '<tr class="pt-2"><td  width="250px"><select class="form-control select2 warehouse-product" required data-item="'+$target+'" data-target="'+avaTr+'" name="warehouse_id['+$target+'][]">'+optionName+'</select></td><td  width="100px" class="px-3"><input type="number"  required class="form-control text-center" disabled id="available_qty_'+avaTr+'" type="number" /></td><td  width="100px" class="px-3"><input type="number"  required class="form-control text-center" name="unload_qty['+$target+'][]" type="number" /></td><td><a class="btn btn-danger delete-row btn-sm" href="javascript:void(0)"> <i class="fa fa-trash"></i> </a></td></tr>';

                    $("#warehouse_"+$content).append(html);

                    $('.select2').select2();
                }else{
                    bootbox.alert("{{__('common.already_warehouse_load_qty_assigned')}}");
                }
            });


            function  checkLoadQty($target) {
                var allTr =  $("#warehouse_"+$target).find("tr");
                var totalQty = 0;
                warehouseSelected[$target] = [];

                for (var $i=1; $i<=allTr.length; $i++)
                {
                    var warehouseId = $("#warehouse_"+$target)
                        .find("tr:nth-child("+$i+") td:first-child select :selected").val();
                    warehouseSelected[$target].push(warehouseId);

                    var qty = $("#warehouse_"+$target)
                        .find("tr:nth-child("+$i+") td:nth-child(3) input").val();

                    totalQty = parseFloat(totalQty)+parseFloat(qty);
                }

                return totalQty;
            }

            var url = "{{ route('warehouse.item_details') }}";

            $(document).on('change', '.warehouse-product', function(e){
                e.preventDefault();

                var warehouse = $(this).val();
                var id = $(this).data('target');
                var itemId = $(this).data('item');

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'item_id' : itemId,
                    'warehouse_id' : warehouse,
                };


                console.log(form_data);

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
                                $('#available_qty_'+id).val(data.stock);
                            }else{
                                bootbox.alert("{{__('common.out_of_stock')}} ");
                            }
                        }else{
                            bootbox.alert("{{__('common.no_data_found')}} ");
                        }
                    });


                e.stopImmediatePropagation();
                return false;
            });


        });


    </script>

@endpush


