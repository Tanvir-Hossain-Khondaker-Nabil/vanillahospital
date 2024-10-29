<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 07-Mar-20
 * Time: 12:25 PM
 */

$route = \Auth::user()->can(['member.doctor_schedule.index']) ? route('member.doctor_schedule.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Doctor Schedule',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Doctor Schedule',
    'title' => 'List Of Doctor Schedule',
    'heading' => 'List Of Doctor Schedule',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Doctor Schedule Details</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-responsive table-striped">

                        <tr>
                            <th colspan="1" width="250px"> Name </th>
                            <td colspan="3"> {{$doctor_schedule->doctor->name}} </td>
                        </tr>
                        <tr>
                            <th colspan="1" width="250px"> Degree </th>
                            <td colspan="3"> {{$doctor_schedule->doctor->degree}} </td>
                        </tr>

                        <tr>
                            <th colspan="1" width="250px"> Address </th>
                            <td colspan="3"> {{$doctor_schedule->doctor->address}} </td>
                        </tr>
                        <tr>
                            <th colspan="1" width="250px"> Mobile </th>
                            <td colspan="3"> {{$doctor_schedule->doctor->mobile}} </td>
                        </tr>
                        <tr>
                            <th colspan="1" width="250px"> Consult Fee </th>
                            <td colspan="3"> {{$doctor_schedule->doctor->consult_fee}} </td>
                            <th>Fee Old Patient</th>
                            <td>{{$doctor_schedule->doctor->fee_old_patient}}</td>
                            <th>Fee Only Report</th>
                            <td>{{$doctor_schedule->doctor->fee_only_report}}</td>

                        </tr>

                    </table>
                </div>
                <!-- /.box-body -->
            </div>

            <div class="box box-primary">

                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-responsive table-striped">

                        <tr>
                            <th>#SL</th>
                            <th>Day </th>
                            <th> Time </th>
                            <th> Action </th>

                        </tr>

                        <tbody>
                            {{-- @foreach (get_daysOfWeek() as $item) --}}
                            @foreach ($doctor_schedule->scheduleDay as $key=> $val)
                            <tr>
                                    {{-- @if (in_array($item, $val->pluck('day_name')->toArray())) --}}
                                    <td>
                                        {{++ $key }}
                                    </td>
                                    <td>
                                        {{ $val->day_name }}
                                    </td>
                                        <td>
                                            {{ $val->start_time }} - {{ $val->end_time }}
                                        </td>
                                        <td>
                                            @if (\Auth::user()->can(['member.doctor_schedule.edit']))
                                            <a class="btn btn-xs btn-success"
                                                href="{{ route('member.doctor_schedule_day.edit', [$doctor_schedule->id,$val->id]) }}"><i
                                                    class="fa fa-edit" title='Show'></i>
                                            </a>
                                        @endif


                                        </td>

                                    {{-- @endif --}}
                                </tr>
                                    @endforeach
                            {{-- @endforeach --}}

                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>

        </div>
    </div>
    <!-- /.row -->
@endsection

@push('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js" type="text/javascript"></script>

    <script src="{{ asset('public/adminLTE/bower_components/Flot/jquery.flot.js') }}"></script>
    <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
    <script src="{{ asset('public/adminLTE/bower_components/Flot/jquery.flot.resize.js') }}"></script>
    <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
    <script src="{{ asset('public/adminLTE/bower_components/Flot/jquery.flot.pie.js') }}"></script>
    <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
    <script src="{{ asset('public/adminLTE/bower_components/Flot/jquery.flot.categories.js') }}"></script>
    <script>
        $(function() {
            singleProjectData();
            /*
             * DONUT CHART
             * -----------
             */
            // let aa= 40;


            function singleProjectData() {
                let url = "{{ route('member.singleProject') }}";
                let auth = {!! \Auth::user()->hasRole(['project_manager']) ? 1 : 0 !!}
                if (auth) {
                    url = "{{ route('member.employee.singleProject') }}";
                }

                let id = $('#project_id').val();
                $.ajax({
                    url: url,
                    method: 'get',
                    data: {
                        'id': id,
                    },
                    success: function(data) {

                        var donutData = [{
                                label: 'To Do',
                                data: data.data.total_to_do,
                                color: '#3c8dbc'
                            },
                            {
                                label: 'In Progress',
                                data: data.data.total_in_progress,
                                color: '#0073b7'
                            },
                            {
                                label: 'Review',
                                data: data.data.total_review,
                                color: '#00c0ef'
                            },
                            {
                                label: 'Done',
                                data: data.data.total_done,
                                color: '#25ab17'
                            }
                        ]

                        $.plot('#donut-chart', donutData, {
                            series: {
                                pie: {
                                    show: true,
                                    radius: 1,
                                    innerRadius: 0.5,
                                    label: {
                                        show: true,
                                        radius: 2 / 3,
                                        formatter: labelFormatter,
                                        threshold: 0.1
                                    }

                                }
                            },
                            legend: {
                                show: false
                            }
                        })
                    }
                });
            }


            function labelFormatter(label, series) {
                return '<div style="font-size:13px; text-align:center; padding:2px; color: #fff; font-weight: 600;">' +
                    label +
                    '<br>' +
                    Math.round(series.percent) + '%</div>'
            }

            /*
             * END DONUT CHART
             */
        })
    </script>
@endpush
