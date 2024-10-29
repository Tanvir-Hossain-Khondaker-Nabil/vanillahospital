<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

 $route =  \Auth::user()->can(['member.procurements.index']) ? route('member.procurements.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";


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
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Procurements/Requisition Budget',
    'title' => 'List Of Procurements/Requisition Budget',
    'heading' => 'List Of Procurements/Requisition Budget',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="portlet light">

                    @if(\Auth::user()->can(['member.procurements.create']))
                    <div class="box-header">
                        <a href="{{ route('member.procurements.create') }}" class="btn btn-info"> <i class="fa fa-plus"> Add
                                Procurements/Requisition Budget </i></a>
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

    <script>
        function onApproved(e, id) {
            bootbox.confirm("Are you sure? ", function(result) {

                if (result) {
                    $.ajax({
                        url: "{{ route('member.procurements_budget_approve') }}",
                        type: "POST",
                        dataType: "json",
                        data:{ _token: "{{ csrf_token() }}", id:id},
                        success: function(data) {
                            bootbox.alert("Approved successfully completed",
                                function() {
                                    location.reload();
                                });
                        },
                        error: function(xhr, textStatus, errorThrown) {
                            var response = xhr.responseJSON;
                            let _Message = response.data.message

                            if (_Message == "" || _Message == undefined)
                                bootbox.alert("OOP! Sorry Response Denied");
                            else
                                bootbox.alert(_Message);
                        }
                    });
                } else {
                    bootbox.alert("OOP! Sorry Response Denied");
                }
            });
        }
    </script>
@endpush
