<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.vehicle_info.index']) ? route('member.vehicle_info.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Vehicle Info',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Vehicle Info',
    'title' => 'List Of Vehicle Info',
    'heading' => 'List Of Vehicle Info',
];

?>

@extends('layouts.back-end.master', $data)


@section('contents')
<style>
    /* width */
    ::-webkit-scrollbar {
        height: 6px !important;
        width: 0px !important;

    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #dbdbdb !important;
        border-radius: 10px !important;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #6AA0BF !important;
        border-radius: 10px !important;
        padding: 0 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #6AA0BF !important;
    }

    div.dataTables_wrapper div.dataTables_length label {
        font-weight: normal !important;
        text-align: left !important;
        white-space: nowrap !important;
    }

    label {
        max-width: 100% !important;
        margin-bottom: 5px !important;
        font-weight: 700 !important;
    }

    div.dataTables_wrapper div.dataTables_length select {
        width: 75px !important;
        display: inline-block !important;
    }

    @media (min-width: 768px) {
        .form-inline .form-control {
            display: inline-block !important;
            width: auto !important;
            vertical-align: middle !important;
        }
    }

    select.input-sm {
        height: 30px !important;
        line-height: 30px !important;
    }

    .table-bordered>thead>tr>th,
    .table-bordered>tbody>tr>th,
    .table-bordered>tfoot>tr>th,
    .table-bordered>thead>tr>td,
    .table-bordered>tbody>tr>td,
    .table-bordered>tfoot>tr>td {
        border: 1px solid #f4f4f4 !important;
    }

    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        padding: 8px !important;
        line-height: 1.42857143 !important;
        vertical-align: top !important;
        border-top: 1px solid #ddd !important;
    }

    th {
        text-align: left !important;
    }

    table.dataTable {
        clear: both !important;
        margin-top: 6px !important;
        margin-bottom: 6px !important;
        max-width: none !important;
        border-collapse: separate !important;
    }

    .table-bordered {
        border: 1px solid #f4f4f4 !important;
    }

    table {
        /* background-color: transparent; */
    }

    .table-bordered>thead>tr>th,
    .table-bordered>tbody>tr>th,
    .table-bordered>tfoot>tr>th,
    .table-bordered>thead>tr>td,
    .table-bordered>tbody>tr>td,
    .table-bordered>tfoot>tr>td {
        border: 1px solid #f4f4f4 !important;
    }

    .table>thead>tr>th,
    .table>tbody>tr>th,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>tbody>tr>td,
    .table>tfoot>tr>td {
        border-top: 1px solid #f4f4f4 !important;
    }

</style>
<div class="row">
    <div class="col-xs-12">

        @include('common._alert')
        @csrf
        <div class="row">
            <div class="col-md-12">

                <div class="box">
                    <div class="box-header">
                        <div class="box-header">
                            @if (\Auth::user()->can(['member.doctors.create']))
                            <a href="{{ route('member.vehicle_info.create') }}" class="btn btn-info"> <i class="fa fa-plus">
                                </i> Add Vehicle Info</a>
                            @endif

                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="row px-3">
                        <div class="col-sm-6">
                            <div class="dataTables_length" id="vanilla-table1_length">
                                <label>Show
                                    <select name="vanilla-table1_length" id="table_sorting" class="form-control input-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> entries</label></div>
                        </div>
                        <div class="col-sm-6" style="display: flex; justify-content: end;">
                            <div id="vanilla-table1_filter" class="dataTables_filter">
                                <label>Search:
                                    <input type="search" class="form-control input-sm" placeholder="" aria-controls="vanilla-table1">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div id="table_data" class="box-body table-responsive relative">

                        @include('member.vehicle_info.table')

                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection


@push('scripts')
{{-- <script>
    $(function() {
        $("#vanilla-table1").DataTable({
            // "lengthMenu":[ 3,4 ],
            "searching": true
        , });
        $("#vanilla-table2").DataTable({

            "searching": true
        , });

    });

</script> --}}
<script>
    $(document).ready(function() {

        $(document).on('click', '.page-link', function(e) {
            e.preventDefault()



            var page = $(this).attr('href').split('page=')[1];

            if (page) {
                $('#vanilla_table').css('opacity', '0.5');
                $("#processing").removeClass('d-none');


                $('.page-item').removeClass('active');
                $(this).parent().addClass('active');

                $('.page-item').removeClass('disabled');
                fetch_data(page);
            }


        })

        function fetch_data(page) {
            var _token = $("input[name='_token']").val()
            $.ajax({
                url: "{{ url('member/pagination_fatch') }}"
                , method: "POST"
                , data: {
                    _token: _token
                    , page: page
                }
                , success: function(data) {
                    $('#table_data').html(data)
                }
            })
        }

        $('#table_sorting').on('change', async function() {
            let table_sorting = $(this).val()
            $("#processing").removeClass('d-none')
            $('#vanilla_table').css('opacity', '.5')
            $.ajax({
                url: "{{ url('member/sorting_fatch/') }}" + '/' + table_sorting
                , method: "GET"
                , success: function(data) {
                    $('#table_data').html(data)
                }
            })
        })
    })

</script>


@endpush
