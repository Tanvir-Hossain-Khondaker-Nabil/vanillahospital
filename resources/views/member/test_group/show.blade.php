<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 07-Mar-20
 * Time: 12:25 PM
 */

$route = \Auth::user()->can(['member.test_group.index']) ? route('member.test_group.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Lead',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Test Group',
    'title' => 'List Of Test Group',
    'heading' => 'List Of Test Group',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')
<div class="row">
    <div class="col-md-12">

        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Sub Test Group({{$title}})</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="vanilla-table1" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#SL</th>
                            <th>Test Group Title</th>
                            <th>Sub Test Group Title</th>
                            <th>Price</th>
                            <th>Unit</th>
                            <th>Quack Ref Com</th>
                            <th>Ref Val</th>
                            <th>Room No</th>

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($sub_test_groups as $key => $list)
                            <tr>
                                {{-- {{dd($list)}} --}}
                                <td>{{ ++$key }}</td>
                                <td>{{ $list->testGroup? $list->testGroup->title : "" }}({{$list->testGroup?$list->testGroup->specimen->specimen : ""}})</td>
                                <td>{{ $list->title }}</td>
                                <td>{{ $list->price }}</td>
                                <td>{{ $list->unit }}</td>
                                <td>{{ $list->quack_ref_com }}</td>
                                <td>{{ $list->ref_val }}</td>
                                <td>{{ $list->room_no }}</td>

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
