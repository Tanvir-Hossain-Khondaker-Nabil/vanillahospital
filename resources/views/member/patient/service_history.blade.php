<?php


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
    'title' => 'Service History',
    'heading' => 'Service History',
];

?>

@extends('layouts.back-end.master', $data)


@section('contents')
    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="row">
                <div class="col-md-12">
                    <div class="col-12 ">
                        <form action="{{ route('member.service_search') }}" target="_blank" method="post">
                            @csrf
                            <div class="box py-3 px-3">
                                <div class="row" style="    display: flex;align-items: end;">
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="from">From Date </label>
                                            <input id="from" value="{{ isset($inputdata)?$inputdata['from']:'' }}" class="form-control date"
                                                placeholder="Select Form Date" name="from" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="to">To Date </label>
                                            <input id="to" value="{{ isset($inputdata)?$inputdata['to']:'' }}" class="form-control date"
                                                placeholder="Select To Date" name="to" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="id">Patient Id</label>
                                            <input id="id" value="{{ isset($inputdata)?$inputdata['id']:'' }}" class="form-control"
                                                placeholder="Enter Patient ID" name="id" type="text">
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
                    </div>

                    <div class="box">
                        <div class="box-header">

                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="vanilla-table1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Patient ID</th>
                                        <th>Order Id</th>
                                        <th>Service</th>
                                        <th>Marketing Officer</th>
                                        <th>Doctor</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Total Price</th>
                                        <th>Added By</th>
                                        {{-- <th>Action</th> --}}


                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($serviceHistory as $key => $list)
                                        <tr>
                                            {{-- {{dd($list)}} --}}
                                            <td>{{ ++$key }}</td>
                                            <td>{{ isset($list->patient)?$list->patient->patient_info_id:'' }}</td>
                                            <td>{{ $list->order_id }}</td>
                                            <td>{{ isset($list->service)?$list->service->title:''}}</td>
                                            <td>{{ isset($list->marketingOfficer)?$list->marketingOfficer->name:'' }}</td>
                                            <td>{{ isset($list->doctor)?$list->doctor->name:'' }}</td>

                                            <td>{{ $list->price }}</td>
                                            <td>{{ $list->qty }}</td>
                                            <td>{{ $list->qty*$list->price }}</td>

                                            <td>{{ isset($list->user)?$list->user->full_name:'' }}</td>

                                            {{-- <td>
                                                @if (\Auth::user()->can(['member.patient_registration.edit']))
                                                    <a class="btn btn-xs btn-success"
                                                        href="{{ route('member.service_order_show', $list->id) }}"><i
                                                            class="fa fa-eye" title='Edit'></i>
                                                    </a>
                                                @endif
                                            </td> --}}

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
        $(".date").datepicker({
            autoclose: true,
        });
    </script>


    </script>
@endpush
