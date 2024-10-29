<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 2/26/2019
 * Time: 2:45 PM
 */

$route = \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";
$home1 = \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Dashboard',
        'href' => $route,
    ]
];

$data['data'] = [
    'name' => 'Dashboard',
    'title' => 'Dashboard',
    'heading' => 'HR Dashboard',
];


?>
@extends('layouts.back-end.master', $data)

@push('styles')

    <link rel="stylesheet" href="{{ asset('public/gantt_chart/gantt.min.css')}}">
@endpush

@section('contents')

<div class="row">
    <div class="col-md-12">

        <div class="box">
            <div class="box-body pb-0">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-12 px-0 text-right">

                            <a class="btn btn-success btn-sm"
                               href="{{ url()->current() }}?view_item=graphical"> {{ __('dashboard.graphical_analysis') }}</a>

                            <a class="btn btn-info btn-sm"
                               href="{{ url()->current() }}?view_item=normal">{{ __('dashboard.normal_analysis') }}</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
                <div class="col-md-12">
                    <div class="row">

                        <div class="col-md-6">
                            <!-- Donut chart -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{__('common.number_of_employee_by_year')}}</h3>
                                </div>
                                <div class="box-body">
                                    <div id="yearoptions" style="height: 250px;"></div>
                                </div>
                                <!-- /.box-body-->
                            </div>
                        </div>

                        <div class="col-md-6">
                            <!-- Donut chart -->
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">{{__('common.department_staff_composition')}}</h3>
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
                                    <h3 class="box-title">{{__('common.salary_system_composition')}}</h3>
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
                                    <h3 class="box-title">{{__('common.number_of_employees_by_monthly_salary')}}</h3>
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

                        </div>

                        <div class="col-md-6">
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
    <script src="{{ asset('public/adminLTE/bower_components/canvus/canvus.js') }}"></script>
    <script src="{{ asset('public/adminLTE/bower_components/apexchart/apexchart.js') }}"></script>
    <script>

        var colorCodes = [
            '#8b8dfa', '#a9a9a9', '#faa62d', "#26c95e",
            "#e15757", "#dc0000", '#b158da', '#00b0b0',
            '#E91E63',"#304758"]

        $(function () {

            var empSalaryRange = @php print_r(json_encode(collect($employeeSalaryRanges)->pluck('count'))) @endphp;
            var empSalaryRangeTitle = @php print_r(json_encode(collect($employeeSalaryRanges)->pluck('salary_range'))) @endphp;

            var options = {
                series: [{
                    data: empSalaryRange
                }],
                chart: {
                    type: 'bar',
                    height: 200,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 0,
                        borderRadiusApplication: 'end',
                        horizontal: true,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: empSalaryRangeTitle,
                }
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();

            var employeeJoinYearCount = @php print_r($employeeJoinYearCount) @endphp;
            employeeJoinYearCount = JSON.parse(employeeJoinYearCount);
            console.log(employeeJoinYearCount);
            var yearoptions = {
                series: [{
                    name: '{{__("common.yearly_joined")}}',
                    data: employeeJoinYearCount
                }],
                chart: {
                    height: 350,
                    type: 'bar',
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        borderRadius: 10,
                        dataLabels: {
                            position: 'top', // top, center, bottom
                        }
                    },
                },
                stroke: {
                    width: 0,
                },
                dataLabels: {
                    enabled: true,
                    formatter: function (val) {
                        return val;
                    },
                    offsetY: -20,
                    style: {
                        fontSize: '13px',
                        colors: ["#304758"]
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return val
                        }
                    }
                },
                fill: {
                    opacity: 1,
                    colors: ['#E91E63']
                },
                xaxis: {
                    labels: {
                        formatter: function(val) {
                            return val
                        }
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#yearoptions"), yearoptions);
            chart.render();


            var employeeSalarySystemCount = @php print_r(json_encode(collect($employeeSalarySystemCount)->pluck('total_employees'))) @endphp;
            var employeeSalarySystem = @php print_r(json_encode(collect($employeeSalarySystemCount)->pluck('salary_system'))) @endphp;

            var options = {
                series: employeeSalarySystemCount,
                chart: {
                    width: 400,
                    type: 'pie',
                },
                colors: colorCodes,
                labels: employeeSalarySystem,
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

            var chart = new ApexCharts(document.querySelector("#priority-chart"), options);
            chart.render();

            var total_tasks = 0;
            var total_high = 0;
            var total_low = 0;
            var total_medium = 0;

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


            // var priorityData = [
            //     {
            //         label: 'High',
            //         data: total_high,
            //         color: '#dc0000'
            //     },
            //     {
            //         label: 'Low',
            //         data: total_low,
            //         color: '#26c95e'
            //     },
            //     {
            //         label: 'Medium',
            //         data: total_medium,
            //         color: '#f6a23d'
            //     }
            // ];
            //
            //
            // $.plot('#priority-chart', priorityData, {
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

            var total_expense = 0;
            var total_budget = 90;

            var empDepartmentCount = @php print_r(json_encode(collect($employeeDepartments)->pluck('count'))) @endphp;
            var empDepartments = @php print_r(json_encode(collect($employeeDepartments)->pluck('name'))) @endphp;

            var moneyoptions = {
                series: [{
                    data: empDepartmentCount
                }],
                chart: {
                    type: 'bar',
                    height: 380,
                    toolbar: {
                        show: false
                    }
                },
                plotOptions: {
                    bar: {
                        barHeight: '100%',
                        distributed: true,
                        horizontal: true,
                        dataLabels: {
                            position: 'bottom'
                        },
                    }
                },
                colors: colorCodes,
                dataLabels: {
                    enabled: true,
                    textAnchor: 'start',
                    style: {
                        colors: ['#000']
                    },
                    formatter: function (val, opt) {
                        return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val
                    },
                    offsetX: 0,
                    dropShadow: {
                        enabled: false
                    }
                },
                stroke: {
                    width: 1,
                    colors: ['#fff']
                },
                xaxis: {
                    categories: empDepartments,
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

    {{--    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>--}}

    {{--    <script type="module" defer src="{{ asset('public/gantt_chart/VanillaGanttChart.js')}}"></script>--}}
    {{--    <script  type="module" defer src="{{ asset('public/gantt_chart/index.js')}}"></script>--}}
    {{--    <script src="{{ asset('public/gantt_chart/gantt.js')}}"></script>--}}
    {{--    <script src="{{ asset('public/gantt_chart/initialize-gantt.js')}}"></script>--}}
@endpush


