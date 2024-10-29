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

                    <div class="box-header">

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

    <div class="modal fade" id="previwModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">List of Budget</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Month</th>
                                <th>Product Name</th>
                                <th>Deparment</th>
                                <th>Unit</th>
                                <th>Quantity</th>
                                <th>Price</th>
                            </tr>
                        </thead>
                        <tbody class="pro-data-append">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
        function onNotApproved(e, id) {
            bootbox.confirm("Are you sure? ", function(result) {

                if (result) {
                    $.ajax({
                        url: "{{ route('member.procurements_budget_not_approve') }}",
                        type: "POST",
                        dataType: "json",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: id
                        },
                        success: function(data) {
                            bootbox.alert("Not Approved successfully completed",
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

        function onPreview(e, id) {
            let data = $(e).data('full');
            let html = '';
            if(data){
                $.each(data.procurement_details,function(index,value){
                    html = `<tr>
                                <td>${data.year}</td>
                                <td>${moment.months()[data.month]}</td>
                                <td>${value.item.item_name}</td>
                                <td>${value.department.name}</td>
                                <td>${value.item.unit}</td>
                                <td>${value.qty}</td>
                                <td>${value.amount}</td>
                            </tr>`

                });

            }
            $(".pro-data-append").html(html)
            $("#previwModal").modal('show')

        }
    </script>
@endpush
