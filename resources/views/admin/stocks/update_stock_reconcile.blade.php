<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 04-Feb-20
 * Time: 12:28 PM
 */
 $route =  \Auth::user()->can(Route::current()->getName()) ? route(Route::current()->getName()) : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => "Yearly Stock Reconcile",
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => "Yearly Stock Reconcile",
    'title'=> "Yearly Stock Reconcile",
    'heading' => trans('common.yearly_stock_reconcile'),
];

?>

@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')
            @include('common._error')

            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title"> {{__('common.stock_damage_tracking')}}</h3>
                </div>


            {!! Form::open(['route' => 'admin.update_stock_reconcile','method' => 'POST', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        @if(config('settings.warehouse'))
                            <div class="col-md-3">
                                <label>  {{__('common.warehouse_name')}} </label>
                                {!! Form::select('warehouse_id', $warehouses, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                            </div>
                        @endif

                        <div class="col-md-3">
                            <label>  {{__('common.product_name')}} </label>
                            {!! Form::select('item_id', $items, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>
                        <div class="col-md-3">
                            <label>  {{__('common.damage_qty')}} </label>
                            <input class="form-control " name="qty" value="" autocomplete="off" placeholder='{{__("common.enter_loss_qty")}}'/>
                        </div>

                    </div>
                    <!-- /.row -->
                </div>
                <div class="box-body">

                    <div class="col-md-3">
                        <input class="btn  btn-sm btn-info" value="{{__('common.update')}}" type="submit"/>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>




            <div class="box box-default">

                <div class="box-header with-border">
                    <h3 class="box-title"> {{__('common.stock_overflow_tracking')}}</h3>
                </div>


            {!! Form::open(['route' => 'admin.update_stock_overflow_reconcile','method' => 'POST', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <label>  {{__('common.product_name')}} </label>
                            {!! Form::select('item_id', $items, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>
                        <div class="col-md-3">
                            <label>  {{__('common.stock_overflow_qty')}} </label>
                            <input class="form-control " name="qty" value="" autocomplete="off" placeholder='{{__("common.enter_gain_qty")}}'/>
                        </div>

                    </div>
                    <!-- /.row -->
                </div>
                <div class="box-body">

                    <div class="col-md-3">
                        <input class="btn  btn-sm btn-info" value="{{__('common.update')}}" type="submit"/>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready( function(){
            $('.select2').select2();
            $('.date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });
        });
    </script>
@endpush



