<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.appoinments.index']) ? route('member.appoinments.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Appoinment',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Appoinment',
    'title' => 'List Of Appoinment',
    'heading' => 'List Of Appoinment',
];

?>

@extends('layouts.back-end.master', $data)


@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-12">

                    <div class="box">
                        <div class="box-header">
                            <div class="box-header">
                                @if (\Auth::user()->can(['member.appoinments.create']))
                                    <a href="{{ route('member.appoinments.create') }}" class="btn btn-info"> <i
                                            class="fa fa-plus">
                                        </i> Add Appoinment</a>
                                @endif

                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <div class="py-3">
                                <form id="formSubmit" action="{{ route('member.appoinments.index') }}" method="GET" onchange="this.submit();">
                                    <div class="card-body border-bottom row">
                                        <div class="col-lg-3 col-md-3 col-12">
                                            <label>{{ __('Appointment Date') }}</label>
                                            <input autocomplete="off" id="date" name="date" type="text" placeholder="{{ __('Appointment Date') }}" class="form-control"
                                                value="{{ request('date') }}">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-12">
                                            <label>{{ __('Patient Name') }}</label>
                                            <input name="patient_name" type="text" placeholder="{{ __('Patient Name') }}" class="form-control"
                                                value="{{ request('patient_name') }}">
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-12">
                                            <label>{{ __('Doctor Name') }}</label>

                                            <select name="doctor_id" class="form-control select2">
                                                <option value="">
                                                    Please Select
                                                 </option>
                                                @foreach ($doctors as $key=> $value)

                                                <option value="{{$value->id}}">
                                                   {{$value->name}}
                                                </option>
                                                @endforeach

                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-3 col-12" style="margin-top: 22px">
                                            <button type="submit" class="btn btn-success">Search</button>
                                        </div>
                                    </div>
                                </form>
                            </div>


                            <table id="vanilla-table1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Appointment Id</th>
                                        <th>Patient Name</th>
                                        <th>Mobile</th>
                                        <th>Doctor Name</th>
                                        <th>Schedule</th>
                                        <th>Fee</th>
                                        <th>Discount</th>
                                        <th>Total</th>
                                        <th>Serial No</th>
                                        <th>Date</th>
                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($appoinments as $key => $list)
                                        <tr>
                                            {{-- {{dd($list)}} --}}
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $list->appointment_id }}</td>
                                            <td>{{ $list->patient_name }}</td>
                                            <td>{{ $list->phone }}</td>
                                            <td>{{ $list->doctor ? $list->doctor->name : '' }}</td>
                                            <td>{{ $list->schedule }}</td>
                                            <td>{{ $list->fee }}</td>
                                            <td>{{ $list->discount }}</td>
                                            <td>{{ $list->gross_total }}</td>
                                            <td>{{ $list->serial_no }}</td>
                                            <td>{{ date_month_year_format($list->date) }}</td>


                                            <td>

                                                @if (\Auth::user()->can(['member.appoinments.edit']))
                                                    <a class="btn btn-xs btn-success"
                                                        href="{{ route('member.appoinments.edit', $list->id) }}"><i
                                                            class="fa fa-edit" title='Edit'></i>
                                                    </a>
                                                @endif

                                                @if (\Auth::user()->can(['member.appoinments.show']))
                                                    <a class="btn btn-xs  btn-primary"
                                                        href="{{ route('member.appoinments.show', $list->id) }}"> <i class="fa fa-print" aria-hidden="true" title='Show'></i>

                                                    </a>
                                                @endif

                                                @if (\Auth::user()->can(['member.appoinments.destroy']))
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-xs btn-danger delete-confirm"
                                                        data-target="{{ route('member.appoinments.destroy', $list->id) }}">
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
        </div>
    </div>
@endsection


@push('scripts')
    <script>

$('#date').datepicker({
            //   format: 'L',
            //    minDate: new Date(),
                //  "minDate": new Date(),
                // "setDate": new Date(),
                "format": 'dd/mm/yyyy',
                // "endDate": "+0d",
                // "todayHighlight": true,
                // "autoclose": true
                "showTime": true,
                "startDate": "+0d",
            });

        $(function() {
            $("#vanilla-table1").DataTable({
                // "lengthMenu":[ 3,4 ],
                "searching": true,
            });
            $("#vanilla-table2").DataTable({

                "searching": true,
            });

        });
    </script>


    </script>
@endpush
