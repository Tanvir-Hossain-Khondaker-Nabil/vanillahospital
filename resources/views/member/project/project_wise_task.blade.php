<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 07-Mar-20
 * Time: 12:25 PM
 */

$route = \Auth::user()->can(['member.project.index']) ? route('member.project.index') : "#";
$home1 = \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Project Task',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Project Task',
    'title' => 'Project Task',
    'heading' => 'Project: '.$project->project,
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-check-square-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">To Do</span>
                    <h3 class="my-1">{{$total_to_do}}</h3>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-blue"><i class="fa fa-tasks"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">In Progress</span>
                    <h3 class="my-1">{{$total_in_progress}}</h3>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-navy"><i class="fa fa-paper-plane-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Review</span>
                    <h3 class="my-1">{{$total_review}}</h3>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-check-circle"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">Done</span>
                    <h3 class="my-1">{{$total_done}}</h3>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
    </div>
    <div class="row">

        <div class="col-md-6">
            <!-- Donut chart -->
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title py-2">Upcoming Task</h3>

                    <table class="table table-responsive table-striped">
                        {{-- {{dd($project)}} --}}
                        <thead>
                        <tr class="">

                            <th> Task</th>
                            <th> Employee</th>
                            <th> Start Date</th>
                            <th> End Date</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($upcoming as $row)
                        {{-- {{dd($row)}} --}}
                        @if (!empty($row->task))
                        <tr>

                            <td> {{$row->task?$row->task->title : ''}} </td>
                            <td> {{$row->employee?$row->employee->first_name : ''}} </td>
                            <td>{{$row->task?$row->task->start_date : ''}}</td>
                            <td>{{$row->task?$row->task->end_date : ''}}</td>

                        </tr>
                        @endif


                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">

            <!-- Donut chart -->
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title py-2">Overdue Task</h3>

                    <table class="table table-responsive table-striped">
                        {{-- {{dd($project)}} --}}
                        <thead>
                        <tr class="">
                            <th> Task</th>
                            <th> Employee</th>
                            <th> Start Date</th>
                            <th> End Date</th>
                            <th> Status</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($overdue as $row)
                        @if (!empty($row->task))
                        <tr>

                            <td> {{$row->task?$row->task->title : ''}} </td>
                            <td> {{$row->employee?$row->employee->first_name : ''}} </td>
                            <td>{{$row->task?$row->task->start_date : ''}}</td>
                            <td>{{$row->task?$row->task->end_date : ''}}</td>
                            <td>
                                @if ($row->status == 'review')
                                 <span class="badge" style="background-color: #001f3f">Review</span>
                                @elseif ($row->status == 'to_do')
                                <span class="badge" style="background-color: #dd4b39">To Do</span>

                                @elseif ($row->status == 'in_progress')
                                <span class="badge" style="background-color: #0073b7">In Progress</span>
                                @endif

                            </td>

                        </tr>
                        @endif

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Donut chart -->
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title py-2">Complete Task</h3>

                    <table class="table table-responsive table-striped">
                        {{-- {{dd($project)}} --}}
                        <thead>
                        <tr class="">

                            <th> Task</th>
                            <th> Employee</th>
                            <th> Start Date</th>
                            <th> End Date</th>

                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($complete as $row)
                            <tr>
                                <td> {{$row->task?$row->task->title : ''}} </td>
                                <td> {{$row->employee?$row->employee->first_name : ''}} </td>
                                <td>{{$row->task?$row->task->start_date : ''}}</td>
                                <td>{{$row->task?$row->task->end_date : ''}}</td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
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
        let ssd = '';
        $(function () {
            singleProjectData();
            /*
             * DONUT CHART
             * -----------
             */

            // let aa= 40;


            function singleProjectData() {
                // alert('lll');
                let id = $('#project_id').val();
                $.ajax({
                    url: "{{ route('member.singleProject') }}",
                    method: 'get',
                    data: {
                        'id': id,
                    },
                    success: function (data) {
                        // ssd = data.data
                        // console.log('dddd', data.data.total_to_do)
                        // console.log('dddd', data)
                        //  let total_to_do = 8;
                        //  console.log('total_to_do',total_to_do)
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

            console.log('ppppp', ssd);

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
