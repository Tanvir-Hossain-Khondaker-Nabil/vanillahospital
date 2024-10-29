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
        'name' => 'Transfer',
    ],
];

$data['data'] = [
    'name' => 'Transfer Warehouse',
    'title'=> 'Transfer Warehouse',
    'heading' => 'Transfer Warehouse',
];

?>
@extends('layouts.back-end.master', $data)


@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css')}}">
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">

@endpush

@section('contents')

    <div class="box box-default">

        @include('common._alert')

        {!! Form::open(['route' => 'member.warehouse.transfer.store', 'method' => 'POST', 'role'=>'form', 'files'=>true ]) !!}

        <div class="box-body pb-0">
            <div class="row pt-5">

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="inputPassword" > Date</label>
                        {!! Form::text('date',null,['id'=>'date','class'=>'form-control','autocomplete'=>"off",'required']); !!}
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group ">
                        <label for="inputPassword" >From Warehouse</label>
                        {!! Form::select('warehouse_from', $warehouses, null,[ 'class'=>'form-control select2','required', 'id'=>'warehouse_from', 'placeholder'=>'Select From Warehouse']); !!}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group ">
                        <label for="inputPassword" >To Warehouse</label>
                        {!! Form::select('warehouse_to', $warehouses, null,[ 'class'=>'form-control select2','required', 'id'=>'warehouse_to', 'placeholder'=>'Select To Warehouse']); !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group ">
                        <label for="inputPassword" >Select Products</label>
                        <div class="input-group input-group-sm">

                            <select class="form-control select2"  id="product_id" >
                                <option value=""> Select Product</option>
                            @foreach($products as $value)
                                    <option data-value="{{$value->unit}}" value="{{$value->id}}"> {{$value->item_name}}</option>
                            @endforeach

                            </select>
                            <span class="input-group-btn">
                              <button type="button" class="btn btn-block" id="add_to_transfer" style="height:35px;padding: 8px 20px;"> Add</button>
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- /.row -->
        <div class="row">


            <div class="pl-5 col-lg-9 col-md-9 col-sm-12 col-sx-12  new-table-responsive text-center">

                <h4>Transfer Itemwise  </h4>

                <table class="table table-responsive table-striped">


                <thead>
                    <tr>
                        <th class="text-center" width="40px">#SL</th>
                        <th class="text-left">Product</th>
                        <th class="text-center" width="100px">Unit</th>
                        <th class="text-center" width="100px">Qty</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="transfer-list">

                </tbody>
                </table>

            </div>

        </div>



            <div style="margin-top: 20px; " class="row">

                <div class="col-lg-12 col-md-12 "   style="max-width: 100%; flex: 100%;">
                    <table class="new-table-3 pull-right" style="margin-top: 20px; margin-bottom: 20px"  >
                        <tr>
                            <td>
                                <button style="width: 150px !important; margin-right: 30px;" type="submit" class="btn btn-success" disabled>Complete Transfer </button>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>



        {!! Form::close() !!}


    </div>



@endsection

@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <!-- Date range picker -->
    <script type="text/javascript">

        $(function () {

            let option  = "<option value='' >Select to Warehouse </option>";



            @foreach($warehouses as $key=>$value)
                option += "<option value='{{$key}}' >{{$value}}</option>";

            @endforeach


            $('#date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });

            $('.select2').select2();

            $("#warehouse_from").change( function () {
                let from = $("#warehouse_from :selected").val();
                $("#warehouse_to").html(option);
                $("#warehouse_to").select2();
                $("#warehouse_to option[value="+from+"]").attr('disabled', true);
            });


            $("#add_to_transfer").click( function () {

                var product_id = $("#product_id :selected").val();
                if(product_id>0)
                {
                    let listTrlength = $("#transfer-list").find('tr').length+1;

                    var product_text = $("#product_id :selected").text();
                    var unit = $("#product_id :selected").data('value');

                    let html = "<tr><th style='text-align:center; vertical-align: middle;'>"+listTrlength+"</th><th  class='text-left my-auto' style='vertical-align: middle;'><input type='hidden' class='form-control' name='product_id[]' required value='"+product_id+"'>"+product_text+"</th><td class=' my-auto'  style='vertical-align: middle;'>"+unit+"</td><td><input type='number' class='form-control text-center' required  width='100px' name='unload_qty[]' value='' /></td><td><a href='javascript:void(0)' class='btn btn-sm btn-danger delete-field'><i class='fa fa-trash'></i> </a> </td></tr>";

                    $("#transfer-list").append(html);

                    $("#product_id option[value="+product_id+"]").remove();

                    $(".btn.btn-success").attr('disabled',false);
                }
            });

        });

        function checkTrSerial()
        {
            let listTrans = $("#transfer-list").find('tr');
            let listTranslength = $("#transfer-list").find('tr').length;

            for(var i=1; i<=listTranslength; i++)
            {
                $("#transfer-list").find('tr:nth-child('+i+')').find("th:first-child").html(i);
            }
        }


        $(document).on('click','.delete-field', function(e) {
            e.preventDefault();
            var $div = $(this).parent().parent();
            $div.remove();

            let listTr = $("#transfer-list").find('tr');

            if(listTr.length<1)
            {
                $(".btn.btn-success").attr('disabled',true);
            }

            checkTrSerial();
        });
    </script>

@endpush


