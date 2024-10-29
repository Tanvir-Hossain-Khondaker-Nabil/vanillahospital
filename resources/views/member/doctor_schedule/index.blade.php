<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */

$route = \Auth::user()->can(['member.opd.due.list']) ? route('member.opd.due.list') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'OPD Due List',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'OPD Due List',
    'title' => 'List Of OPD Due List',
    'heading' => 'List Of OPD Due List',
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


                            </div>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <table id="vanilla-table1" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>#SL</th>
                                        <th>Name</th>
                                        <th>Degree</th>
                                        <th>Consult Fee</th>
                                        <th>Emergency Fee</th>
                                        <th>Only Report Fee</th>
                                        <th>Old Patient Fee</th>
                                        <th>Action</th>


                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach ($doctor_schedule?? [] as $key => $val )
                                   <tr>
                                   <td>{{ ++$key }}</td>

                                   <td>{{ $val->doctor->name }}</td>
                                   <td>{{ $val->doctor->degree }}</td>
                                   <td>{{ $val->doctor->consult_fee }}</td>
                                   <td>{{ $val->doctor->emergency_fee }}</td>
                                   <td>{{ $val->doctor->fee_only_report }}</td>
                                   <td>{{ $val->doctor->fee_old_patient }}</td>
                                   <td>
                                    @if (\Auth::user()->can(['member.doctor_schedule.show']))
                                        <a class="btn btn-xs btn-success"
                                            href="{{ route('member.doctor_schedule.show', $val->id) }}"><i
                                                class="fa fa-eye" title='Show'></i>
                                        </a>
                                    @endif
                                    @if (\Auth::user()->can(['member.doctor_schedule.destroy']))
                                        <a href="javascript:void(0);"
                                            class="btn btn-xs btn-danger delete-confirm"
                                            data-target="{{ route('member.doctor_schedule.destroy', $val->id) }}">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    @endif
                                </tr>
                                </td>
                                   @endforeach
                                    {{-- @foreach ($doctor_schedule ?? [] as $key => $val)
                                        <tr>

                                            <td>{{ ++$key }}</td>

                                            <td>{{ $val->doctor->name }}</td>

                                            @foreach (get_daysOfWeek() as $item)
                                                @if (in_array($item, $val->scheduleDay->pluck('day_name')->toArray()))
                                                    <td>
                                                        {{ $val->start_time }} - {{ $val->end_time }}
                                                    </td>
                                                @else
                                                    <td>
                                                        -
                                                    </td>
                                                @endif

                                            @endforeach

                                            <td>
                                                @if (\Auth::user()->can(['member.doctor_schedule.edit']))
                                                    <a class="btn btn-xs btn-success"
                                                        href="{{ route('member.doctor_schedule.edit', $val->id) }}"><i
                                                            class="fa fa-edit" title='Edit'></i>
                                                    </a>
                                                @endif
                                                @if (\Auth::user()->can(['member.doctor_schedule.destroy']))
                                                    <a href="javascript:void(0);"
                                                        class="btn btn-xs btn-danger delete-confirm"
                                                        data-target="{{ route('member.doctor_schedule.destroy', $val->id) }}">
                                                        <i class="fa fa-times"></i>
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach --}}

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
