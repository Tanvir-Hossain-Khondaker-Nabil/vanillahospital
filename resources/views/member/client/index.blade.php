<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */


 $route =  \Auth::user()->can(['member.client.index']) ? route('member.client.index') : "#";
   $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";


$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Client',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Client',
    'title'=>'List Of Client',
    'heading' => 'List Of Client',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="portlet light">

                    <div class="box-header">
                        @if(\Auth::user()->can(['member.client.create']))
                        <a href="{{ route('member.client.create') }}" class="btn btn-info"> <i class="fa fa-plus"> Add Client </i></a>
                        @endif
                        @if(\Auth::user()->can(['member.label.create']))
                        <a href="#" onclick="manageLabel('client')" class="btn btn-success"> <i class="fa fa-gear"></i>  Manage Labels  </a>
                        @endif

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
                {{-- <p>ffffd</p> --}}
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}

    @include('common.label_script');
@endpush
