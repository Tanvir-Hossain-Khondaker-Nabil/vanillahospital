<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 07-Mar-20
 * Time: 12:25 PM
 */

 $route =  \Auth::user()->can(['member.project.index']) ? route('member.project.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Project',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Project',
    'title' => 'Project',
    'heading' => 'Project',
];

if (isset($project)) {
    $project_image = $project->image != '' ? $project->project_image_path : '';
} else {
    $project_image = '';
}
?>

@extends('layouts.back-end.master', $data)

@section('contents')
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-body box-profile">
                    <div class="box-header with-border mb-3">
                        <h3 class="box-title">Project: {{ $project->project }}</h3>
                    </div>

                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" id="project_id" value="{{ $project->id }}">
                                <img class=" w-100 img-responsive h-100"
                                    src="{{ $project_image == null ? asset('/public/adminLTE/dist/img/building.jpg') : $project_image }}"
                                    alt="User profile picture">

                            </div>
                            <div class="col-md-12 mt-3">
                                <table class="table table-responsive table-striped">
                                    {{-- {{dd($project)}} --}}
                                    <tr>
                                        <th colspan="2"> Project Name </th>
                                        <td colspan="2"> {{ $project->project }} </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2"> Project Category Name </th>
                                        <td colspan="2"> {{ $project->projectCategory->display_name }} </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2"> Client Name </th>
                                        <td colspan="2"> {{ $project->client->name }} </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Address</th>
                                        <td>{{ $project->address }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="2"> Price </th>
                                        <td colspan="2"> {{ create_money_format($project->price) }} </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Start Date</th>
                                        <td colspan="2">{{ $project->start_date }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">End Date</th>
                                        <td colspan="2">{{ $project->expire_date }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Label</th>
                                        <td colspan="2">
                                            @foreach ($project->labeling as $val)
                                                <span style="background-color:{{ $val->label->bg_color }}" class="mt-2 badge large p-2">{{ $val->label->name }}
                                                </span>
                                            @endforeach

                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Status</th>

                                        <td>
                                            <span
                                                style="background-color:{{ $project->status == 'active' ? '#2d9cdb' : '#e18a00' }}"
                                                class="mt-2 badge large p-2">{{ $project->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Progress Status</th>

                                        <td class="text-capitalize">
                                            {{ $project->progress_status }}

                                        </td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">Created By</th>
                                        <td class="text-capitalize">{{ $project->createdBy->full_name }}</td>
                                    </tr>
                                     <tr>
                                        <th colspan="3">
                                            <a class="btn btn-success btn-sm" href="{{ route('member.employee.project_wise_task', $project->id) }}">Task Details</a>

                                            <a class="btn btn-info btn-sm" href="{{ route('member.users.kanban_list', $project->id) }}">Task Kanban</a>

                                        </th>

                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Donut chart -->
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Time graph</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="progress mt-3">
                                            <div class="progress-bar" role="progressbar"
                                                style="width: {{ $percentage_of_day }}%" aria-valuenow="10"
                                                aria-valuemin="0" aria-valuemax="{{ $percentage_of_day }}">Today
                                                {{ $today_date }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p>Start Date</p>
                                                <p class="">{{ $project->start_date }}</p>
                                            </div>
                                            <div class="col-md-6" style="display: grid; justify-content: end">
                                                <p class="">End Date</p>
                                                <p class="">{{ $project->expire_date }}</p>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- /.box-body-->
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Donut chart -->
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">Progress</h3>
                                    </div>
                                    <div class="box-body">
                                        <div id="donut-chart" style="height: 300px;"></div>
                                    </div>
                                    <!-- /.box-body-->
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- Donut chart -->
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title py-2">Project Manager</h3>

                                        <table class="table table-responsive table-striped">
                                            {{-- {{dd($project)}} --}}
                                            <thead>
                                                <tr class="">
                                                    <th> Name </th>
                                                    <th> Status </th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                @foreach ($project_manager as $row)
                                                    <tr>
                                                        <td>

                                                            <span>
                                                                {{ $row->employee->uc_full_name }}
                                                            </span>
                                                        </td>

                                                        <td
                                                        <span class=" badge large p-2 {{$row->status =='active'? 'bg-green' : 'bg-yellow'}}">{{ $row->status }}
                                                        </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>

                                    <!-- /.box-body-->
                                </div>
                            </div>

                            <div class="col-md-12">
                                <!-- Donut chart -->
                                <div class="box box-primary">
                                    <div class="box-header with-border">
                                        <h3 class="box-title py-2">Project Employee</h3>

                                        <table class="table table-responsive table-striped">
                                            {{-- {{dd($project)}} --}}
                                            <thead>
                                                <tr class="">
                                                    <th> Name </th>
                                                    <th> Role </th>
                                                    <th> Pending </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- {{dd($project->task->groupBy('employee_info_id'))}} --}}
                                                @foreach ($empployees as $row)
                                                    <tr>
                                                        <td class="d-flex">

                                                            <span>
                                                                {{ $row->employee->first_name }}
                                                                {{ $row->employee->last_name }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $row->employee->designation->name }}</td>
                                                        <td>{{ $row->pending }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>

                                    <!-- /.box-body-->
                                </div>
                            </div>

                        </div>
                    </div>

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
                if (auth){
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
