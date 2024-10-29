<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.opd.due.patient.list']) ? route('member.due.all.patient.list') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'OPD Due Patient List',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'OPD Due Patient List',
    'title' => 'List Of OPD Due Patient',
    'heading' => 'List Of OPD Due Patient',
];

?>

@extends('layouts.back-end.master', $data)


@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-12">


                    <div class="row">
                        <div class="col-md-12">
                            <div class="box">

                                <!-- /.box-header -->
                                <div class="box-body">
                                    <div class="py-5">
                                        <form action="{{ route('member.opd.due.patient.list') }}" method="GET">
                                            @csrf
                                            <div class="card-body border-bottom row">
                                                <div class="col-lg-3 col-md-3 col-12">
                                                    <label>{{ __('From Date') }}</label>
                                                    <input autocomplete="off" id="from_date" name="from_date" type="text"
                                                        placeholder="{{ __('From Date') }}" class="form-control"
                                                        value="{{ $from_date }}">
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-12">
                                                    <label>{{ __('To Date') }}</label>
                                                    <input autocomplete="off" id="to_date" name="to_date" type="text"
                                                        placeholder="{{ __('To Date') }}" class="form-control"
                                                        value="{{ $to_date }}">
                                                </div>

                                                <div class="col-lg-3 col-md-3 col-12">
                                                    <label>{{ __('OPD ID') }}</label>
                                                    <select class="form-control" name="opd_id">
                                                        <option value="">All</option>
                                                        @foreach ($patients as $key=>$value)
                                                        <option {{@$opd_id == $value? 'selected' : ""}} value="{{$value}}">{{$value}}</option>
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
                                                <th>Patient Name</th>
                                                <th>Mobile</th>
                                                <th>Gender</th>
                                                <th>Age</th>
                                                <th>Blood Group</th>
                                                <th>Date of Service</th>
                                                <th>Address</th>
                                                <th>Image</th>
                                                <th>Action</th>

                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($datas as $key => $list)
                                                <tr>
                                                    {{-- {{dd($list)}} --}}
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $list->patient_name }}</td>
                                                    <td>{{ $list->phone }}</td>
                                                    <td>{{ $list->gender }}</td>
                                                    <td>{{ $list->age }}</td>
                                                    <td>{{ $list->bllod_group }}</td>
                                                    <td>{{ $list->date_of_service }}</td>
                                                    <td>{{ $list->address }}</td>

                                                    <td>
                                                        <img src="{{ asset('uploads/patient/' . $list->image) }}">
                                                    </td>

                                                    <td>


                                                        {{-- @if (\Auth::user()->can(['member.opd.patient.print'])) --}}
                                                        <a class="btn btn-xs  btn-primary"
                                                            href="{{ route('member.out_door_registration.show', $list->id) }}">
                                                            <i class="fa fa-print" aria-hidden="true" title='Show'></i>

                                                        </a>
                                                        {{-- @endif --}}

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
