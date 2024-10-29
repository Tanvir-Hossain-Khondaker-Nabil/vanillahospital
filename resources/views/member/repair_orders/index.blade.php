<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 5/26/2019
 * Time: 3:04 PM
 */

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => route('admin.dashboard'),
        'icon' => 'fa fa-home',
    ],
    [
        'name' => trans('common.repairing_orders'),
        'href' => route('member.repair_orders.index'),
    ],
    [
        'name' => trans('common.list'),
    ],
];

$data['data'] = [
    'name' => trans('common.repairing_orders'),
    'title'=>  trans('common.repairing_orders'),
    'heading' => trans('common.list_of_order_repair'),
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="portlet light">

                    @if(Auth::user()->can(['admin','super-admin']))
                    <div class="box-header">
                        <a href="{{ route('member.repair_orders.create') }}" class="btn btn-info"> <i class="fa fa-plus"> {{__('common.create_order')}} </i></a>
                    </div>
                    @endif
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
@endpush


