<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 4/24/2019
 * Time: 12:28 PM
 */



 $route =  \Auth::user()->can(['member.quotation_terms.index']) ? route('member.quotation_terms.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Quotation Terms',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Quotation Terms',
    'title'=>'List Of Quotation Terms',
    'heading' => 'List Of Quotation Terms',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="portlet light">

                    @if(\Auth::user()->can(['member.quotation_terms.create']))
                    <div class="box-header">
                        <a href="{{ route('member.quotation_terms.create') }}" class="btn btn-info"> <i class="fa fa-plus"> Add Quotation Terms </i></a>
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
