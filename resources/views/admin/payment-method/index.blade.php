<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */


 $route =  \Auth::user()->can(['admin.payment_methods.index']) ? route('admin.payment_methods.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Payment Methods',
        'href' => $route,
    ],

    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Payment Method',
    'title'=>'List Of Payment Methods',
    'heading' => 'List Of Payment Methods',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
{{--                <div class="portlet light">--}}

{{--                    <div class="box-header">--}}
{{--                        <a href="{{ route('admin.payment_methods.create') }}" class="btn btn-info"> <i class="fa fa-plus"> Add Payment Method </i></a>--}}
{{--                    </div>--}}
{{--                    <!-- END SAMPLE FORM PORTLET-->--}}
{{--                </div>--}}
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
