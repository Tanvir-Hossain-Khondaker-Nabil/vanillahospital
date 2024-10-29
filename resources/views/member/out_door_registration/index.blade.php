<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.out_door_registration.index']) ? route('member.out_door_registration.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Outdoor Registration',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Outdoor Registration',
    'title' => 'List Of Outdoor Registration',
    'heading' => 'List Of Outdoor Registration',
];

?>

@extends('layouts.back-end.master', $data)


@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-12">

                    <div class="box-header">
                        <div class="box-header">
                            @if (\Auth::user()->can(['member.out_door_registration.create']))
                                <a href="{{ route('member.out_door_registration.create') }}" class="btn btn-info"> <i
                                        class="fa fa-plus">
                                    </i> Add Outdoor Registration</a>
                            @endif

                        </div>
                    </div>

                    <div class="panel panel-default form component main">
                        <div class="panel-heading">
                            <ul id="rowTab" class="nav nav-tabs">
                                <li class="{{ @$opd_type == 'all_opd_list' ? 'active' : '' }} "><a data-toggle="tab"
                                        href="#all_opd_list">All OPD List</a></li>
                                {{-- <li class=""><a data-toggle="tab" href="#paid_opd_list">Paid OPD List</a></li> --}}
                                <li class="{{ @$opd_type == 'due_opd_list' ? 'active' : '' }}"><a data-toggle="tab"
                                        href="#due_opd_list">Due OPD List</a></li>

                            </ul>
                            <div class="tab-content">
                                <div id="all_opd_list" class="tab-pane fade {{ @$opd_type == 'all_opd_list' ? 'active' : '' }} in py-5">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="box">


                                                <div class="box-body">
                                                    <table id="vanilla-table1" class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>#SL</th>
                                                                <th>OPD ID</th>
                                                                <th>Patient Name</th>
                                                                <th>Mobile</th>
                                                                <th>Service Date</th>
                                                                <th>Doctor Name</th>
                                                                <th>Ref. Doctor Name</th>
                                                                <th>IPD Patient</th>
                                                                <th>Member Patient</th>
                                                                <th>total Amount</th>
                                                                <th>Total Paid</th>
                                                                <th>Discount</th>
                                                                <th>Due</th>
                                                                <th>Action</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @foreach ($all_outdoor as $key => $list)
                                                                <tr>
                                                                    {{-- {{dd($list)}} --}}
                                                                    <td>{{ ++$key }}</td>
                                                                    <td>{{ $list->opd_id }}</td>
                                                                    <td>{{ $list->patient_name }}</td>
                                                                    <td>{{ $list->phone }}</td>
                                                                    <td>{{ $list->date_of_service }}</td>
                                                                    <td>{{ $list->doctor ? $list->doctor->name : '' }}</td>
                                                                    <td>{{ $list->refDoctor ? $list->refDoctor->name : '' }}
                                                                    </td>
                                                                    <td>{{ $list->ipd_patient }}</td>
                                                                    <td>{{ $list->member_patient }}</td>
                                                                    <td>{{ $list->total_amount }}</td>
                                                                    <td>{{ $list->total_paid }}</td>
                                                                    <td>{{ $list->discount }}</td>
                                                                    <td>{{ $list->due }}</td>

                                                                    <td>


                                                                        @if (\Auth::user()->can(['member.out_door_registration.edit']))
                                                                            <a class="btn btn-xs  btn-primary"
                                                                                href="{{ route('member.out_door_registration.edit', $list->id) }}">
                                                                                <i class="fa fa-edit" aria-hidden="true"
                                                                                    title='Edit'></i>

                                                                            </a>
                                                                        @endif

                                                                        @if (\Auth::user()->can(['member.out_door_registration.show']))
                                                                            <a class="btn btn-xs  btn-primary"
                                                                                href="{{ route('member.out_door_registration.show', $list->id) }}">
                                                                                <i class="fa fa-print" aria-hidden="true"
                                                                                    title='Show'></i>

                                                                            </a>
                                                                        @endif

                                                                        @if (\Auth::user()->can(['member.out_door_registration.destroy']))
                                                                            <a href="javascript:void(0);"
                                                                                class="btn btn-xs btn-danger delete-confirm mt-1"
                                                                                data-target="{{ route('member.out_door_registration.destroy', $list->id) }}">
                                                                                <i class="fa fa-times"></i>
                                                                            </a>
                                                                        @endif

                                                                        <a class="btn btn-xs  btn-success mt-1"
                                                                        href="{{ route('member.opd.test.barcode.print', $list->id) }}">
                                                                        <i class="fa fa-barcode" aria-hidden="true" title="Barcode Print"></i>

                                                                    </a>
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


                                {{-- <div id="paid_opd_list" class="tab-pane fade in py-5">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="box">

                                                <div class="box-body">
                                                    <table id="vanilla-table1" class="table table-bordered table-hover">
                                                        <thead>
                                                            <tr>
                                                                <th>#SL</th>
                                                                <th>OPD ID</th>
                                                                <th>Patient Name</th>
                                                                <th>Mobile</th>
                                                                <th>Service Date</th>
                                                                <th>Doctor Name</th>
                                                                <th>Ref. Doctor Name</th>
                                                                <th>IPD Patient</th>
                                                                <th>Member Patient</th>
                                                                <th>total Amount</th>
                                                                <th>Total Paid</th>
                                                                <th>Discount</th>
                                                                <th>Due</th>
                                                                <th>Action</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @foreach ($outdoor_register as $key => $list)
                                                                <tr>

                                                                    <td>{{ ++$key }}</td>
                                                                    <td>{{ $list->opd_id }}</td>
                                                                    <td>{{ $list->patient_name }}</td>
                                                                    <td>{{ $list->phone }}</td>
                                                                    <td>{{ $list->date_of_service }}</td>
                                                                    <td>{{ $list->doctor ? $list->doctor->name : '' }}</td>
                                                                    <td>{{ $list->refDoctor ? $list->refDoctor->name : '' }}
                                                                    </td>
                                                                    <td>{{ $list->ipd_patient }}</td>
                                                                    <td>{{ $list->member_patient }}</td>
                                                                    <td>{{ $list->total_amount }}</td>
                                                                    <td>{{ $list->total_paid }}</td>
                                                                    <td>{{ $list->discount }}</td>
                                                                    <td>{{ $list->due }}</td>

                                                                    <td>


                                                                        @if (\Auth::user()->can(['member.out_door_registration.show']))
                                                                            <a class="btn btn-xs  btn-primary"
                                                                                href="{{ route('member.out_door_registration.show', $list->id) }}">
                                                                                <i class="fa fa-eye" aria-hidden="true"
                                                                                    title='Show'></i>

                                                                            </a>
                                                                        @endif

                                                                        @if (\Auth::user()->can(['member.out_door_registration.destroy']))
                                                                            <a href="javascript:void(0);"
                                                                                class="btn btn-xs btn-danger delete-confirm"
                                                                                data-target="{{ route('member.out_door_registration.destroy', $list->id) }}">
                                                                                <i class="fa fa-times"></i>
                                                                            </a>
                                                                        @endif
                                                                    </td>

                                                                </tr>
                                                            @endforeach

                                                        </tbody>

                                                    </table>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}

                                <div id="due_opd_list" class="tab-pane fade {{ @$opd_type == 'due_opd_list' ? 'active' : '' }} in py-5">
                                    <div class="row">
                                        <div class="box">
                                            <div class="box-header">
                                                <div class="py-5">
                                                    <form action="{{ route('member.out_door_registration.index') }}"
                                                        method="GET">
                                                        @csrf
                                                        <div class="card-body border-bottom row">
                                                            <div class="col-lg-3 col-md-3 col-12">
                                                                <label>{{ __('From Date') }}</label>
                                                                <input autocomplete="off" id="from_date" name="from_date"
                                                                    type="text" placeholder="{{ __('From Date') }}"
                                                                    class="form-control" value="{{ $from_date }}">
                                                            </div>

                                                            <div class="col-lg-3 col-md-3 col-12">
                                                                <label>{{ __('To Date') }}</label>
                                                                <input autocomplete="off" id="to_date" name="to_date"
                                                                    type="text" placeholder="{{ __('To Date') }}"
                                                                    class="form-control" value="{{ $to_date }}">
                                                            </div>

                                                            <div class="col-lg-3 col-md-3 col-12">
                                                                <label>{{ __('OPD ID') }}</label>
                                                                <select class="form-control" name="opd_id">
                                                                    <option value="">Please Select</option>
                                                                    @foreach ($patients as $key => $value)
                                                                        <option {{ @$opd_id == $value ? 'selected' : '' }}
                                                                            value="{{ $value }}">
                                                                            {{ $value }}</option>
                                                                    @endforeach


                                                                </select>
                                                            </div>

                                                            <div class="col-lg-2 col-md-3 col-12" style="margin-top: 22px">
                                                                <button type="submit"
                                                                    class="btn btn-success">Search</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="box-body">

                                                <table id="vanilla-table2" class="table table-bordered table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>#SL</th>
                                                            <th>OPD ID</th>
                                                            <th>Patient Name</th>
                                                            <th>Mobile</th>
                                                            <th>Service Date</th>
                                                            <th>Doctor Name</th>
                                                            <th>Ref. Doctor Name</th>
                                                            <th>IPD Patient</th>
                                                            <th>Member Patient</th>
                                                            <th>total Amount</th>
                                                            <th>Total Paid</th>
                                                            <th>Discount</th>
                                                            <th>Due</th>
                                                            <th>Action</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @foreach ($outdoor_due_list as $key => $list)
                                                            <tr>

                                                                <td>{{ ++$key }}</td>
                                                                <td>{{ $list->opd_id }}</td>
                                                                <td>{{ $list->patient_name }}</td>
                                                                <td>{{ $list->phone }}</td>
                                                                <td>{{ $list->date_of_service }}</td>
                                                                <td>{{ $list->doctor ? $list->doctor->name : '' }}</td>
                                                                <td>{{ $list->refDoctor ? $list->refDoctor->name : '' }}
                                                                </td>
                                                                <td>{{ $list->ipd_patient }}</td>
                                                                <td>{{ $list->member_patient }}</td>
                                                                <td>{{ $list->total_amount }}</td>
                                                                <td>{{ $list->total_paid }}</td>
                                                                <td>{{ $list->discount }}</td>
                                                                <td>{{ $list->due }}</td>

                                                                <td>



                                                                    <a class="btn btn-xs  btn-primary"
                                                                        href="{{ route('member.opd.due.update', $list->id) }}">
                                                                        <i class="fa fa-money" aria-hidden="true"
                                                                            title='Show'></i>

                                                                    </a>

                                                                    <a class="btn btn-xs  btn-success"
                                                                        href="{{ route('member.opd.due.print', $list->id) }}">
                                                                        <i class="fa fa-print" aria-hidden="true"
                                                                            title='Print'></i>

                                                                    </a>





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
                    </div>













                </div>

            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $('#from_date').datepicker({

            // "format": 'yyyy-mm-dd',
            "format": 'mm/dd/yyyy',
            "showTime": true,
            // "startDate": "+0d",
        });

        $('#to_date').datepicker({

            // "format": 'yyyy-mm-dd',
            "format": 'mm/dd/yyyy',
            "showTime": true,
            // "startDate": "+0d",
        });

        $(function() {
            $("#vanilla-table1").DataTable({
                // "lengthMenu":[ 3,4 ],
                "searching": true,
            });
            $("#vanilla-table2").DataTable({

                "searching": true,
            });
            $("#vanilla-table3").DataTable({

                "searching": true,
            });

        });
    </script>


    </script>
@endpush
