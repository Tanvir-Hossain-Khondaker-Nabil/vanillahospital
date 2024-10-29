<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.otd.due.list']) ? route('member.otd.due.list') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'OTD Due List',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'OTD Due List',
    'title' => 'List Of OTD Due List',
    'heading' => 'List Of OTD Due List',
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

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            {{-- <div class="box box-primary">
                                <div class="box-body box-profile pt-4 pb-0">
                                    <div class="col-md-3">

                                        <img style="height: 100px" class="profile-user-img img-responsive img-circle" src="{{ $doctor->image == null ? asset("/public/adminLTE/dist/img/avatar5.png") : asset('/public/uploads/doctor/'.$doctor->image)}}" alt="User profile picture">

                                    </div>
                                    <div class="col-md-9">
                                        <table class="table table-responsive table-striped">

                                            <tr>
                                                <th>{{__('common.name')}} </th>
                                                <td colspan="3">{{ $doctor->name }} ({{ $doctor->degree }})</td>
                                            </tr>
                                            <tr>
                                                <th>{{__('common.phone')}} </th>
                                                <td>{{ $doctor->mobile }}</td>
                                                <th>{{__('common.address')}} </th>
                                                <td>{{ $doctor->address }}</td>
                                            </tr>
                                            <tr>
                                                <th>Consult Fee</th>
                                                <td>{{ $doctor->consult_fee }}</td>

                                            </tr>



                                        </table>
                                    </div>
                                </div>


                            </div> --}}

                            {{-- <caption>{{$comission->doctor->name}}</caption> --}}
                            <table id="vanilla-table1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Patient Name</th>
                                        <th>Mobile</th>
                                        <th>Service Date</th>
                                        <th>Doctor Name</th>
                                        <th>Ref. Doctor Name</th>
                                        <th>IPD Patient</th>
                                        <th>Member Patient</th>
                                        <th>Total Paid</th>
                                        <th>Discount</th>
                                        <th>Due</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($outdoor_register as $key => $list)
                                        <tr>
                                            {{-- {{dd($list)}} --}}
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $list->patient_name }}</td>
                                            <td>{{ $list->phone }}</td>
                                            <td>{{ $list->date_of_service }}</td>
                                            <td>{{ $list->doctor? $list->doctor->name : "" }}</td>
                                            <td>{{ $list->refDoctor ? $list->refDoctor->name : '' }}</td>
                                            <td>{{ $list->ipd_patient }}</td>
                                            <td>{{ $list->member_patient }}</td>
                                            <td>{{ $list->total_paid }}</td>
                                            <td>{{ $list->discount }}</td>
                                            <td>{{ $list->due }}</td>

                                            <td>


                                                {{-- @if (\Auth::user()->can(['member.otd.due.list.create'])) --}}
                                                    <a class="btn btn-xs  btn-primary"
                                                        href="{{ route('member.opd.due.update', $list->id) }}"> <i class="fa fa-money" aria-hidden="true" title='Show'></i>

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
@endsection


@push('scripts')
    <script>
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
