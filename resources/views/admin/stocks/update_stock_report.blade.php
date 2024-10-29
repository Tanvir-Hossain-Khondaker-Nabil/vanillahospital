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
        'name' => "Update Stock Report",
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => "Update Stock Report",
    'title'=> "Update Stock Report",
    'heading' => trans('common.update_stock_report'),
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

            <div class="box box-default">


            {!! Form::open(['route' => 'admin.stock_report_update','method' => 'POST', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <label>  {{__('common.product_name')}} </label>
                            {!! Form::select('item_id', $items, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>
                        <div class="col-md-3">
                            <label> {{__('common.from_date')}} ({{__("common.transaction")}})</label>
                            <input class="form-control date" name="start_date" value="" autocomplete="off"/>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <div class="box-body">

                    <div class="col-md-3">
                        <input class="btn  btn-sm btn-info" value="{{__('common.search')}}" type="submit"/>
                        <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> {{__('common.reload')}}</a>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>


            @if(config('settings.warehouse'))

            <div class="box box-default">

            {!! Form::open(['route' => 'admin.warehouse_update_stock_report','method' => 'POST', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-3">
                            <label>  {{__("common.warehouse_name")}} </label>
                            {!! Form::select('warehouse_id', $warehouses, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>

                        <div class="col-md-3">
                            <label>  {{__('common.product_name')}} </label>
                            {!! Form::select('item_id', $products, null,['id'=>'item_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>
                        <div class="col-md-3">
                            <label> {{__('common.from_date')}} ({{__("common.transaction")}})</label>
                            <input class="form-control date" name="start_date" value="" autocomplete="off"/>
                        </div>
                    </div>
                    <!-- /.row -->
                </div>
                <div class="box-body">

                    <div class="col-md-3">
                        <input class="btn  btn-sm btn-info" value="{{__('common.search')}}" type="submit"/>
                        <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> {{__('common.reload')}}</a>

                    </div>
                </div>

                {!! Form::close() !!}
            </div>

            @endif



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



