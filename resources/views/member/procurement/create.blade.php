<?php


$route =  \Auth::user()->can(['member.items.index']) ? route('member.items.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Procurements/Requisition Budget',
        'href' => $route,
    ],
    [
        'name' => 'Create',
    ],
];

$data['data'] = [
    'name' => 'Procurements/Requisition Budget',
    'title'=>'Create Procurements/Requisition Budget',
    'heading' => 'Create Procurements/Requisition Budget',
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
                <h3 class="box-title">Create Procurements/Requisition Budget</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->

            {!! Form::open(['route' => 'member.procurements.store', 'method' => 'POST', 'role'=>'form', 'id' => "pro-store" ]) !!}

                <div class="box-body">

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="year">Year <span class="text-red"> * </span> </label>
                            {!! Form::select('year',$years, 0,['id'=>'year','class'=>'form-control select2','placeholder'=>'Please select']); !!}
                        </div>

                        <div class="form-group">
                            <label for="month">Month</label>
                            {!! Form::select('month',$months, date("m")-1,['id'=>'month','class'=>'form-control select2','placeholder'=>'Please select']); !!}
                        </div>
                        <div class="form-group">
                            <label for="department_id">Department</label>
                            {!! Form::select('department_id',$departments, null,['id'=>'department_id','class'=>'form-control select2','placeholder'=>'Please select']); !!}
                        </div>
                        <hr>
                        <div class="form-group">

                            <table width="100%">
                                <thead>
                                <tr>
                                    <th width="200px"> Product Title</th>
                                    <th width="200px" class="px-3"> Product Code</th>
                                    <th width="150px" class="px-3"> Unit Price</th>

                                </tr>
                                </thead>
                                <tbody id="">
                                    <tr class="my-2">
                                        <td>
                                            <select onchange="onSelectBudget()" id="product_id" class="form-control select2" placeholder='Please select'>
                                                <option value="">Please Select</option>
                                                @foreach ($items as $val)
                                                <option data-prcode='{{$val->productCode}}' data-skcode='{{$val->skuCode}}' data-price='{{$val->price}}' value="{{$val->id}}">{{$val->item_name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="px-3">
                                            {!! Form::text('product_code', null,['id'=>'product_code','class'=>'form-control','placeholder'=>'Product Code' ,'readonly']); !!}
                                        </td>
                                        <td class="px-3">
                                            <input class="form-control" id="price" step="any" placeholder = "Unit Price" type="number" min="0">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group">
                            <table width="100%">
                                <thead>
                                <tr>
                                    <th width="170px" >Sku Code</th>
                                    <th width="180px" class="px-3">Quantity</th>
                                    <th width="180px" class="px-3">Amount</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody id="">
                                    <tr class="my-2">
                                        <td>
                                            <input class="form-control" id="skucode" step="any" readonly type="text" min="0">
                                        </td>
                                        <td class="px-3">
                                            <input class="form-control" oninput="onChangeAmount(this)" id="qty" step="any" type="number" min="0">
                                        </td>
                                        <td class="px-3">
                                            <input class="form-control" readonly id="total_amount" step="any" type="number" min="0">
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm multi-row" onclick="onAppend()" href="javascript:void(0)" data-content="0"> <i class="fa fa-clone"></i> </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h4>List of Budget</h4>
                        <div class="form-group">
                            <table width="100%" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="appendBudget">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="col-md-12 text-right">
                            <button type="button" onclick="onSubmit()" id="load-submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>

                </div>


            {!! Form::close() !!}
        <!-- /.box -->
    </div>
    </div>
</div>
@endsection


@push('scripts')

    <!-- Select2 -->
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- CK Editor -->
    <script src="{{ asset('public/adminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>


    <script type="text/javascript">
        $(function () {

            $('.select2').select2()

            $('#date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });






        });
        function onChangeAmount(e){
            let product_id   = $('#product_id').val();
            let price        = $('#product_id option:selected').data('price');
            e.value = e.value.replace(/[^1-9]/g, '').replace(/(\..*)\./g, '$1');

            if(e.value && product_id && price){
                $("#total_amount").val(price*e.value)
            }


        }
        function onSelectBudget(){
            let product_id   = $('#product_id').val();
            let product_name = $('#product_id option:selected').text();
            let prcode       = $('#product_id option:selected').data('prcode');
            let skcode       = $('#product_id option:selected').data('skcode');
            let price        = $('#product_id option:selected').data('price');
            if(product_id){
                $("#product_code").val(prcode)
                $("#skucode").val(skcode)
                $("#price").val(price)
                $("#qty").val(1)
                $("#total_amount").val(price)
            }else{
                $("#product_code").val('')
                $("#skucode").val('')
                $("#price").val('')
                $("#qty").val('')
                $("#total_amount").val('')
            }
        }
        let counter = 10002;
        function onAppend(){

            let product_id    = $('#product_id').val();
            let department_id = $('#department_id').val();
            let qty           = $("#qty").val();
            let total_amount  = $("#total_amount").val();
            let count         = 0;
            $('.list-budget').each(function(index){
                if($(this).data('department') == department_id && $(this).data('product') == product_id ){
                    count = 1;
                    return false;
                }
            })
            if(product_id && department_id && qty > 0 && count == 0){
                let department_name = $('#department_id option:selected').text();
                let product_name    = $('#product_id option:selected').text();

                let html = `<tr data-department = '${department_id}' data-product = '${product_id}' class="my-2 list-budget target-${counter}">
                                <td>${department_name}</td>
                                <td>${product_name}</td>
                                <td>${qty}</td>
                                <td>${total_amount}</td>
                                <td>
                                    <input type='hidden' name='deparment_id[]' value='${department_id}'>
                                    <input type='hidden' name='item_id[]' value='${product_id}'>
                                    <input type='hidden' name='qty[]' value='${qty}'>
                                    <input type='hidden' name='total_amount[]' value='${total_amount}'>
                                    <span onclick='onDeleteBudget(".target-${counter++}")' class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></span>
                                </td>
                            </tr>`
                $('#appendBudget').append(html)
            }
        }
        function onDeleteBudget(e){
            $(e).remove();
        }
        function onSubmit(){
            if($('.list-budget').length > 0){
                $("#pro-store").submit();
            }
        }

    </script>


@endpush

