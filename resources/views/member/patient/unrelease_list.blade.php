<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.patient_registration.index']) ? route('member.patient_registration.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Patient',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Patient',
    'title' => 'IPD Unrelease Patient List',
    'heading' => 'IPD Unrelease Patient List',
];

?>

@extends('layouts.back-end.master', $data)


@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-12">
                    {{-- <div class="col-12 ">
                        <form action="{{ route('member.ipd_patient_search') }}" target="_blank" method="post">
                            @csrf
                            <div class="box py-3 px-3">
                                <div class="row" style="    display: flex;align-items: end;">
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="from">From Date </label>
                                            <input id="from" value="{{ isset($inputdata) ? $inputdata['from'] : '' }}"
                                                class="form-control date" placeholder="Select Form Date" name="from"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="to">To Date </label>
                                            <input id="to" value="{{ isset($inputdata) ? $inputdata['to'] : '' }}"
                                                class="form-control date" placeholder="Select To Date" name="to"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="id">Patient Id</label>
                                            <input id="id" value="{{ isset($inputdata) ? $inputdata['id'] : '' }}"
                                                class="form-control" placeholder="Enter Patient ID" name="id"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <button class="btn btn-sm btn-primary" name="btn" value="search"
                                            type="submit">Search</button>
                                        <button class="btn btn-sm btn-success ml-2" name="btn" value="print"
                                            type="submit">Print</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div> --}}

                    <div class="box">
                        <div class="box-header">
                            <div class="box-header">
                                @if (\Auth::user()->can(['member.patient_registration.create']))
                                    <a href="{{ route('member.patient_registration.create') }}" class="btn btn-info"> <i
                                            class="fa fa-plus">
                                        </i> Add Patient</a>
                                @endif

                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body ">
                            <table id="vanilla-table1" class="table table-bordered table-hover table-responsive">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Patient ID</th>
                                        <th>Patient Name</th>
                                        <th>Phone</th>
                                        <th>Email</th>
                                        <th>Reg Form</th>
                                        <th>Service By OPD</th>
                                        <th>Admit Date</th>
                                        <th>Edit</th>
                                        <th>View</th>
                                        <th>Discharge</th>
                                        <th>Service</th>
                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($patinets as $key => $list)
                                        <tr>
                                            {{-- {{dd($list)}} --}}
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $list->patient_info_id }}</td>
                                            <td>{{ $list->patient_name }}</td>
                                            <td>{{ $list->phone }}</td>
                                            <td>{{ $list->email }}</td>
                                            <td> <a href="{{ route('member.patient_form_print', $list->id) }}"
                                                    target="_blank" class="btn btn-info btn-sm">Reg Form </a> </td>
                                            <td></td>
                                            <td>{{ date_month_year_format($list->admit_date_time) }}</td>
                                            <td>
                                                <a href="{{ route('member.patient_form_print', $list->id) }}" class="btn btn-sm btn-info">Edit Info</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('member.patient_form_print', $list->id) }}" class="btn btn-sm btn-success">View Details</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('member.patient_form_print', $list->id) }}" class="btn btn-sm btn-success">Discharge</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('member.add_service', $list->id) }}" class="btn btn-sm btn-success">Add Service</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('member.patient_form_print', $list->id) }}" class="btn btn-sm btn-success">Release</a>
                                            </td>







                                        </tr>
                                    @endforeach

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
    <script>
        $(function() {
            $("#vanilla-table1").DataTable({
                // "lengthMenu":[ 3,4 ],
                // "responsive": true,
                "searching": true,
                "scrollX": true
            });
            $("#vanilla-table2").DataTable({
                "responsive": true,
                "searching": true,
            });

        });
        $(".date").datepicker({
            autoclose: true,
        });
    </script>


    </script>
@endpush
