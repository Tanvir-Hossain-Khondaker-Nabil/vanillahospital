<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('admin.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Warehouse History',
        'href' => route('member.warehouse.index'),
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Warehouse History ',
    'title'=>'Warehouse History',
    'heading' => 'Warehouse History',
];

?>

@push('styles')

    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush


@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="portlet light">

                    <div class="box-header">

                        <div class="box-header with-border">
                            <h3 class="box-title">Search</h3>
                        </div>

                    {!! Form::open(['route' => 'member.warehouse.history.transfer_list','method' => 'GET', 'role'=>'form' ]) !!}
                    <!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <label> From Warehouse Name </label>
                                    {!! Form::select('from_warehouse', $warehouses, null,['id'=>'warehouse_id','class'=>'form-control select2','placeholder'=>'Select All']); !!}
                                </div>
                                <div class="col-md-3">
                                    <label>  To Warehouse Name </label>
                                    {!! Form::select('warehouse_id', $warehouses, null,['id'=>'warehouse_id','class'=>'form-control select2','placeholder'=>'Select All']); !!}
                                </div>

                                <div class="col-md-3 margin-top-23">
                                    <label></label>
                                    <input class="btn btn-info" value="Search" type="submit"/>
                                    <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>

                                </div>
                            </div>
                            <!-- /.row -->
                        </div>
                        {{--<div class="box-body">--}}

                        {{----}}
                        {{--</div>--}}

                        {!! Form::close() !!}

                    </div>
                    <!-- END SAMPLE FORM PORTLET-->
                </div>
                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        // var date = new Date();
        $(function () {

                $('.select2').select2();
        });

    </script>
@endpush
