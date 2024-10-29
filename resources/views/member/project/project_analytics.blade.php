@php
if (isset($project)) {
    $project_image = $project->image != '' ? $project->project_image_path : '';
} else {
    $project_image = '';
}
@endphp

@push('styles')

    <link rel="stylesheet" href="{{ asset('public/gantt_chart/gantt.min.css')}}">
@endpush

<div class="row">

    <div class="col-md-12">

        <div class="box box-primary">
            <div class="box-body box-profile">


                <div class="box-header mb-3">
                    <div class="row">
                        <div class="col-md-9 pr-0">
                            <h3 class="box-title">{{__('common.project')}}: {{ $project->project }}</h3>
                        </div>
                        @if(request()->get('project_id') == "")
                        <div class="col-md-3 px-0 text-right">

                            <a class="btn btn-success btn-sm" target="_blank"
                               href="{{ route('member.employee.project_wise_task', $project->id) }}">{{__('common.task_details')}}</a>

                            <a class="btn btn-info btn-sm" target="_blank"
                               href="{{ route('member.users.kanban_list', $project->id) }}">{{__('common.task_kanban')}}</a>
                        </div>
                        @endif
                    </div>

                </div>


                {{--                    <div class="col-md-12">--}}
                {{--                        <div class="row">--}}
                {{--                            <div class="col-md-2">--}}
                <input type="hidden" id="project_id" value="{{ $project->id }}">
                {{--                                <img class=" w-100 img-responsive h-100"--}}
                {{--                                     src="{{ $project_image == null ? asset('/public/adminLTE/dist/img/building.jpg') : $project_image }}"--}}
                {{--                                     alt="User profile picture">--}}

                {{--                                <table class="table">--}}
                {{--                                    <tr>--}}
                {{--                                        <th>Label</th>--}}
                {{--                                        <td>--}}
                {{--                                            @foreach ($project->labeling as $val)--}}
                {{--                                                <span style="background-color:{{ $val->label->bg_color }}"--}}
                {{--                                                      class="mt-2 badge large p-2">{{ $val->label->name }}--}}
                {{--                                                </span>--}}
                {{--                                            @endforeach--}}

                {{--                                        </td>--}}
                {{--                                    </tr>--}}

                {{--                                    <tr>--}}
                {{--                                        <th colspan="2">Status</th>--}}

                {{--                                        <td>--}}
                {{--                                            <span--}}
                {{--                                                style="background-color:{{ $project->status == 'active' ? '#2d9cdb' : '#e18a00' }}"--}}
                {{--                                                class="mt-2 badge large p-2">{{ $project->status }}--}}
                {{--                                            </span>--}}
                {{--                                        </td>--}}
                {{--                                    </tr>--}}

                {{--                                    <tr>--}}
                {{--                                        <th colspan="2">Progress Status</th>--}}

                {{--                                        <td class="text-capitalize">--}}
                {{--                                            {{ $project->progress_status }}--}}

                {{--                                        </td>--}}
                {{--                                    </tr>--}}
                {{--                                </table>--}}
                {{--                            </div>--}}
                {{--                            <div class="col-md-8">--}}
                {{--                                <table class="table table-responsive table-striped">--}}
                {{--                                    <tr>--}}
                {{--                                        <th colspan="2"> Project Category Name</th>--}}
                {{--                                        <td colspan="2"> {{ $project->projectCategory->display_name }} </td>--}}
                {{--                                        <th colspan="2">Start Date</th>--}}
                {{--                                        <td colspan="2">{{ $project->start_date }}</td>--}}
                {{--                                        <th colspan="2">End Date</th>--}}
                {{--                                        <td colspan="2">{{ $project->expire_date }}</td>--}}
                {{--                                    </tr>--}}
                {{--                                    <tr>--}}
                {{--                                        <th colspan="2"> Client Name</th>--}}
                {{--                                        <td colspan="2"> {{ $project->client->name }} </td>--}}
                {{--                                        <th colspan="2">Address</th>--}}
                {{--                                        <td>{{ $project->address }}</td>--}}
                {{--                                        <th colspan="2"> Price</th>--}}
                {{--                                        <td colspan="2"> {{ create_money_format($project->price) }} </td>--}}
                {{--                                    </tr>--}}



                {{--                                    <tr>--}}
                {{--                                        <th colspan="2">Created By</th>--}}
                {{--                                        <td class="text-capitalize">{{ $project->createdBy->full_name }}</td>--}}
                {{--                                    </tr>--}}
                {{--                                    <tr>--}}
                {{--                                        <th colspan="3">--}}


                {{--                                        </th>--}}

                {{--                                    </tr>--}}
                {{--                                </table>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                    </div>--}}
                <div class="col-md-12">
                    <!-- Donut chart -->
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">{{__('common.time_graph')}}</h3>
                        </div>
                        <div class="box-body pb-0">
                            <div class="progress mt-3">
                                @php
                                    if($percentage_of_day>80){
                                        $progress_bar = "progress-bar-red";
                                    }elseif($percentage_of_day>60){
                                        $progress_bar = "progress-bar-yellow";
                                    }elseif($percentage_of_day>40){
                                        $progress_bar = "progress-bar-aqua";
                                    }else {
                                        $progress_bar = "progress-bar-green";
                                    }

                                    if($project->progress_status == "completed")
                                    {
                                        $progress_bar = "progress-bar-green";
                                    }
                                @endphp
                                <div class="progress-bar {{$progress_bar}}" role="progressbar"
                                     style="width: {{ $percentage_of_day }}%" aria-valuenow="10"
                                     aria-valuemin="0" aria-valuemax="{{ $percentage_of_day }}">{{__('common.today')}}
                                    {{ $today_date }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <p>{{__('common.start_date')}}</p>
                                    <p class="">{{ $project->start_date }}</p>
                                </div>
                                <div class="col-md-4" style="display: grid; justify-content: center">
                                    <p class="text-center"> {{__('common.total')}}: {{ $total_days_of_project }} {{__('common.days')}}</p>
                                    <p class="text-center text-bold {{ $percentage_of_day>70 ? 'text-danger' : 'text-primary' }}"> {{ $due_days_of_project }} {{__('common.days_left_to_complete')}}</p>
                                </div>
                                <div class="col-md-4" style="display: grid; justify-content: end">
                                    <p class="text-right">{{__('common.end_date')}}</p>
                                    <p class="text-right">{{ $project->expire_date }}</p>
                                </div>
                            </div>

                        </div>
                        <!-- /.box-body-->
                    </div>
                </div>
                <div class="col-md-12 pt-2 pb-5">
                    <h4 class="pt-0">{{__('common.task_timeline')}}</h4>
                    <input type="hidden" id="tasks"/>
                    <input type="hidden" id="project_start_date"/>
                    <input type="hidden" id="project_end_date"/>
                    <gantt-chart id="g1"></gantt-chart>
                    {{--                        <div id="chart"></div>--}}
                </div>



                <div class="col-md-12">
                    <div class="row">


                        <div class="col-md-6">
                            <!-- Donut chart -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{__('common.task_status')}} %</h3>
                                </div>
                                <div class="box-body">
                                    {{--                                        <div id="donut-chart" style="height: 300px;"></div>--}}
                                    <div id="chart" style="height: 250px;"></div>

                                    {{--                                        <div id="chartContainer" style="height: 300px; width: 100%;"></div>--}}
                                </div>
                                <!-- /.box-body-->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Donut chart -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{__('common.priority_status')}} %</h3>
                                </div>
                                <div class="box-body">
                                    <div id="priority-chart" style="height: 250px;"></div>
                                </div>
                                <!-- /.box-body-->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Donut chart -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{__('common.budget')}}</h3>
                                </div>
                                <div class="box-body">
                                    <div id="moneyoptions" style="height: 250px;"></div>
                                </div>
                                <!-- /.box-body-->
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!-- Donut chart -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title py-2">{{__('common.project_manager')}}</h3>

                                    <table class="table table-responsive table-striped">
                                        {{-- {{dd($project)}} --}}
                                        <thead>
                                        <tr class="">
                                            <th> {{__('common.name')}}</th>
                                            <th class="text-center text-capitalize"> {{__('common.status')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        @foreach ($project_manager as $row)
                                            <tr>
                                                <td  class="text-left text-capitalize">
                                                    {{ $row->employee->uc_full_name }}
                                                </td>

                                                <td class="text-center text-capitalize {{$row->status =='active'? 'bg-green' : 'bg-yellow'}}">
                                                    {{ $row->status =='active' ? trans('common.active') : $row->status }}
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


                            <div class="box box-danger">
                                <div class="box-header with-border">
                                    <h3 class="box-title py-2">{{__('common.tasks')}}</h3>

                                    <table class="table table-responsive table-striped">
                                        {{-- {{dd($project)}} --}}
                                        <thead>
                                        <tr class="bg-gray">
                                            <th class="text-center"> #SL</th>
                                            <th style="width:400px;"> {{__('common.task_name')}}</th>
                                            <th> {{__('common.assigned_to')}}</th>
                                            <th> {{__('common.start_date')}}</th>
                                            <th> {{__('common.end_date')}}</th>
                                            <th class="text-center"> {{__('common.duration')}}</th>
                                            <th class="text-center"> {{__('common.status')}}</th>
                                            <th class="text-center"> {{__('common.priority')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($tasks as $key => $row)

                                            @php

                                                if ($row->status == 'review'){
                                                    $style="vertical-align:middle; background-color: #f39c12; color: #fff;";
                                                }elseif ($row->status == 'to_do'){
                                                    $style="vertical-align:middle; background-color: #999; color: #fff;";
                                               }elseif ($row->status == 'in_progress'){
                                                   $style="vertical-align:middle; background-color: #3c8dbc; color: #fff;";
                                               }elseif ($row->status == 'done'){
                                                   $style="vertical-align:middle;  background-color: #26c95e;  color: #fff;";
                                               }

                                            if($row->task->end_date<date("Y-m-d") && $row->status != 'done')
                                            {
                                                $style="vertical-align:middle;  background-color: #f6e83ddb;  color: #000;";
                                            }

                                                if ($row->task->priority == 'high'){
                                                    $p_style="background-color: ##c926262b; color: #fff;";
                                                }elseif ($row->task->priority == 'medium'){
                                                    $p_style="background-color: #F6A23D81; color: #000;";
                                               }else{
                                                    $p_style="background-color: #26c95e2b; color: #000;";
                                               }
                                            @endphp
                                            <tr>

                                                <td class="text-center"> {{$key+1}} </td>
                                                <td> {{$row->task?$row->task->title : ''}} </td>
                                                <td> {{$row->employee?$row->employee->first_name : ''}} </td>
                                                <td>{{$row->task?$row->task->start_date : ''}}</td>
                                                <td>{{$row->task?$row->task->end_date : ''}}</td>
                                                <td class="text-center">{{ \Carbon\Carbon::parse($row->task->start_date)->diffInDays(\Carbon\Carbon::parse($row->task->end_date)) }}</td>
                                                <td class="text-center" style="{{$style}}">

                                                    @if($row->task->end_date<date("Y-m-d") && $row->status != 'done')
                                                     {{__('common.overdue')}}
                                                    @else
                                                        {{ $row->task->get_statuses($row->status) }}
                                                    @endif
                                                </td>
                                                <td style="vertical-align: middle; {{ $p_style }}" class="text-center text-capitalize vertical">
                                                    {{ $row->task->priority ?? trans('common.low') }}
                                                </td>

                                            </tr>

                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

{{--                        <div class="col-md-6">--}}
{{--                            <!-- Donut chart -->--}}
{{--                            <div class="box box-primary">--}}
{{--                                <div class="box-header with-border">--}}
{{--                                    <h3 class="box-title py-2">Project Assigned </h3>--}}

{{--                                    <table class="table table-responsive table-striped">--}}
{{--                                        --}}{{-- {{dd($project)}} --}}
{{--                                        <thead>--}}
{{--                                        <tr class="">--}}
{{--                                            <th> Name</th>--}}
{{--                                            <th> Designation</th>--}}
{{--                                            <th> Pending</th>--}}
{{--                                        </tr>--}}
{{--                                        </thead>--}}
{{--                                        <tbody>--}}
{{--                                        --}}{{-- {{dd($project->task->groupBy('employee_info_id'))}} --}}
{{--                                        @foreach ($empployees as $row)--}}
{{--                                            <tr>--}}
{{--                                                <td class="d-flex">--}}

{{--                                                            <span>--}}
{{--                                                                {{ $row->employee->first_name }}--}}
{{--                                                                {{ $row->employee->last_name }}--}}
{{--                                                            </span>--}}
{{--                                                </td>--}}
{{--                                                <td>{{ $row->employee->designation->name }}</td>--}}
{{--                                                <td>{{ $row->pending }}</td>--}}
{{--                                            </tr>--}}
{{--                                        @endforeach--}}
{{--                                        </tbody>--}}

{{--                                    </table>--}}
{{--                                </div>--}}

{{--                                <!-- /.box-body-->--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        {{--                            <div class="col-md-6">--}}
                        {{--                                <!-- Donut chart -->--}}
                        {{--                                <div class="box box-primary">--}}
                        {{--                                    <div class="box-header with-border">--}}
                        {{--                                        <h3 class="box-title py-2">Project Employee</h3>--}}

                        {{--                                        <table class="table table-responsive table-striped">--}}
                        {{--                                            --}}{{-- {{dd($project)}} --}}
                        {{--                                            <thead>--}}
                        {{--                                            <tr class="">--}}
                        {{--                                                <th> Name</th>--}}
                        {{--                                                <th> Designation</th>--}}
                        {{--                                                <th> Pending</th>--}}
                        {{--                                            </tr>--}}
                        {{--                                            </thead>--}}
                        {{--                                            <tbody>--}}
                        {{--                                            --}}{{-- {{dd($project->task->groupBy('employee_info_id'))}} --}}
                        {{--                                            @foreach ($empployees as $row)--}}
                        {{--                                                <tr>--}}
                        {{--                                                    <td class="d-flex">--}}

                        {{--                                                            <span>--}}
                        {{--                                                                {{ $row->employee->first_name }}--}}
                        {{--                                                                {{ $row->employee->last_name }}--}}
                        {{--                                                            </span>--}}
                        {{--                                                    </td>--}}
                        {{--                                                    <td>{{ $row->employee->designation->name }}</td>--}}
                        {{--                                                    <td>{{ $row->pending }}</td>--}}
                        {{--                                                </tr>--}}
                        {{--                                            @endforeach--}}
                        {{--                                            </tbody>--}}

                        {{--                                        </table>--}}
                        {{--                                    </div>--}}

                        {{--                                    <!-- /.box-body-->--}}
                        {{--                                </div>--}}
                        {{--                            </div>--}}

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<!-- /.row -->


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
    <script src="{{ asset('public/adminLTE/bower_components/canvus/canvus.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/apexchart/apexchart.js') }}"></script>
    <script>

        $(function () {

            var total_to_do = {{$total_to_do}};
            var total_in_progress = {{$total_in_progress}};
            var total_review = {{$total_review}};
            var total_done = {{$total_done}};
            var total_overdue = {{$total_overdue}};

            var options = {
                series: [total_to_do, total_in_progress, total_review, total_done, total_overdue],
                chart: {
                    width: 400,
                    type: 'pie',
                },
                colors:['#999', '#3c8dbc', '#f39c12', "#26c95e", "#d93232"],
                labels: ["{{__('common.to_do')}}", "{{__('common.in_progress')}}",  "{{__('common.review')}}", "{{__('common.done')}}", "{{__('common.overdue')}}"],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 200
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

            var total_tasks = {{$total_tasks}};
            var total_high = {{$total_high}};
            var total_low = {{$total_low}};
            var total_medium = {{$total_medium}};

            // var donutData = [
            //     {
            //         label: 'To Do',
            //         data: total_to_do,
            //         color: '#999'
            //     },
            //     {
            //         label: 'In Progress',
            //         data: total_in_progress,
            //         color: '#3c8dbc'
            //     },
            //     {
            //         label: 'Review',
            //         data: total_review,
            //         color: '#f39c12'
            //     },
            //     {
            //         label: 'Done ('+total_done+")",
            //         data: total_done,
            //         color: '#26c95e'
            //     }
            // ];


            // $.plot('#donut-chart', donutData, {
            //     series: {
            //         pie: {
            //             show: true,
            //             radius: 1,
            //             innerRadius: 0.5,
            //             label: {
            //                 show: true,
            //                 radius: 2 / 3,
            //                 formatter: labelFormatter,
            //                 threshold: 0.1
            //             }
            //
            //         }
            //     },
            //     legend: {
            //         show: false
            //     }
            // });


            var priorityData = [
                {
                    label: '{{__("common.high")}}',
                    data: total_high,
                    color: '#dc0000'
                },
                {
                    label: '{{__("common.low")}}',
                    data: total_low,
                    color: '#26c95e'
                },
                {
                    label: '{{__("common.medium")}}',
                    data: total_medium,
                    color: '#f6a23d'
                }
            ];


            $.plot('#priority-chart', priorityData, {
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
            });

            var total_expense = {{ $total_expense }};
            var total_budget = {{ $total_budget }};

            var moneyoptions = {
                series: [{
                    data: [total_budget, total_expense]
                }],
                chart: {
                    type: 'bar',
                    height: 250,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        barHeight: '30%',
                        distributed: true,
                        horizontal: true,
                        dataLabels: {
                            position: 'bottom'
                        },
                    }
                },
                colors: ['#1b9ac7', '#fa3e5b'],
                dataLabels: {
                    enabled: true,
                    textAnchor: 'start',
                    style: {
                        colors: ['#fff']
                    },
                    formatter: function (val, opt) {
                        return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
                    },
                    offsetX: 0,
                    dropShadow: {
                        enabled: true
                    }
                },
                stroke: {
                    width: 1,
                    colors: ['#fff']
                },
                xaxis: {
                    categories: ['{{__("common.panning")}}', '{{__("common.expenses")}}'
                    ],
                },
                yaxis: {
                    labels: {
                        show: false
                    }
                },
                title: {
                    text: '',
                    align: 'center',
                    floating: true
                },
                subtitle: {
                    text: '',
                    align: 'center',
                },
                tooltip: {
                    theme: 'dark',
                    x: {
                        show: false
                    },
                    y: {
                        title: {
                            formatter: function () {
                                return ''
                            }
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#moneyoptions"), moneyoptions);
            chart.render();


            function labelFormatter(label, series) {
                return '<div style="font-size:11px; text-align:center; padding:2px; color: #fff; font-weight: 600;">' +
                    label +
                    '<br>' +
                    Math.round(series.percent) + '%</div>'
            }

            /*
             * END DONUT CHART
             */


            // var chart = new CanvasJS.Chart("chartContainer", {
            //     animationEnabled: true,
            //     title:{
            //         text: "",
            //         horizontalAlign: "left"
            //     },
            //     data: [{
            //         type: "doughnut",
            //         startAngle: 60,
            //         //innerRadius: 60,
            //         indexLabelFontSize: 17,
            //         indexLabel: "{label}:{y} - (#percent%)",
            //         toolTipContent: "<b>{label}:</b> {y} (#percent%)",
            //         dataPoints: [
            //             { y: 67, label: "Inbox" },
            //             { y: 28, label: "Archives" },
            //             { y: 10, label: "Labels" },
            //             { y: 7, label: "Drafts"},
            //             { y: 15, label: "Trash"},
            //             { y: 6, label: "Spam"}
            //         ]
            //     }]
            // });
            // chart.render();

        })
    </script>
@endpush
@push('scripts')

    <script>
        $(function () {
            const $tasks = @json($gantt_chart);

            $("#tasks").val($tasks);
            $("#project_start_date").val("{{ $project_start_date }}");
            $("#project_end_date").val("{{ $project_end_date }}");


            if ($("#tasks").val() != "") {

                // Dynamically create a script element and set its source
                var script = document.createElement("script");
                script.src = "{{ asset('public/gantt_chart/VanillaGanttChart.js')}}";
                script.type = "module";
                document.body.appendChild(script);

                // Dynamically create a script element and set its source
                var script = document.createElement("script");
                script.src = "{{ asset('public/gantt_chart/index.js')}}";
                script.type = "module";
                document.body.appendChild(script);
            }
        });

    </script>
    {{--    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>--}}

    {{--    <script type="module" defer src="{{ asset('public/gantt_chart/VanillaGanttChart.js')}}"></script>--}}
    {{--    <script  type="module" defer src="{{ asset('public/gantt_chart/index.js')}}"></script>--}}
    {{--    <script src="{{ asset('public/gantt_chart/gantt.js')}}"></script>--}}
    {{--    <script src="{{ asset('public/gantt_chart/initialize-gantt.js')}}"></script>--}}
@endpush


