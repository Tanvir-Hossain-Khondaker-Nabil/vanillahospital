<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 11/26/2019
 * Time: 11:40 AM
 */

 $route =  \Auth::user()->can(['member.reconciliation.index']) ? route('member.reconciliation.index') : "#";
 $home1 =  \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Reconciliation',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Reconciliation',
    'title'=>'List Of Reconciliation',
    'heading' => 'List Of Reconciliation',
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

                <div class="box-body">


                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">

                            <table class="table table-responsive table-striped" id="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Date</th>
                                        <th>Transaction Code</th>
                                        <th>Cash or Bank Name</th>
                                        <th>Sharer Name</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready( function(){
            $('.select2').select2();
            $('#date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });


            $('#data-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    URL: "{{ route('member.reconciliation.index') }}",
                    type: 'GET',
                },
                columns: [
                    {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                    {data: 'date', name: 'date'},
                    {data: 'transaction_code', name: 'transaction_code'},
                    {data: 'cash_or_bank', name: 'cash_or_bank','title': 'Cash or Bank Name'},
                    {data: 'sharer', name: 'sharer', 'title':'Sharer name'},
                    {data: 'amount', name: 'amount'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        });
    </script>
@endpush


