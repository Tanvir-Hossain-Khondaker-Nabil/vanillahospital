<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 07-Mar-20
 * Time: 12:25 PM
 */

$route = \Auth::user()->can(['member.lead.index']) ? route('member.lead.index') : '#';
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
    'name' => 'Room List',
    'title' => 'Cabin Class & Room List',
    'heading' => 'Cabin Class & Room List',
];

?>

@extends('layouts.back-end.master', $data)
@push('style')
    <style>
        .title {
            text-transform: capitalize;
            padding: 7px 30px;
            border-radius: 6px;
            margin: 0;
            text-align: center;
            display: inline-block;
            /* width: 223px; */
            margin: auto;
            background: #006769;
            color: white;
            font-size: 25px;
        }

        .sub_title {
            /* padding-left: 42px; */
            font-weight: 700;
            background: #367fa9;
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            font-size: 18px;
            text-transform: capitalize;
        }

        .roomList {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            list-style: none;
            padding-top: 12px;
            padding-bottom: 10px;
            /* border: 1px solid; */
            padding-left: 0;
            /* margin-left: 52px; */
            border-radius: 5px;
            padding-left: 10px;
        }

        .roomList li {
            padding: 10px;
            box-shadow: 0 0 10px 4px #00000020;
            border-radius: 7px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .roomList li i {
            background: #ffffff;
            color: black;
            padding: 10px;
            width: 40px;
            border-radius: 50%;
            height: 40px;
            font-size: 20px;
        }

        .subClass {
            /* border: 1px solid; */
            margin: 20px 0;
            box-shadow: 0 0 10px 2px #00000020;
            border-radius: 5px;
            padding-bottom: 20px;
        }

        .roomList li span {
            font-size: 14px;
            text-transform: capitalize;
        }

        .roomList li.danger {
            background: #FF204E;
            color: white;
        }

        .roomList li.success {
            background: green;
            color: white;
        }
    </style>
@endpush
@section('contents')
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Room List</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body text-center">
                    @foreach ($cabinClass as $class)
                        <h2 class="title">{{ $class->title }}</h2>
                        <div class="subClass">
                            @foreach ($class->subClass as $subClass)
                                <h3 class="sub_title">{{ $subClass->title }}</h3>
                                @php
                                    $rooms = App\Models\Room::where([
                                        ['cabin_sub_class_id', $subClass->id],
                                        ['status', 1],
                                    ])->get();
                                @endphp
                                <ul class="roomList">
                                    @foreach ($rooms as $room)
                                        <li class="{{ $room->is_busy == 1 ? 'danger' : 'success' }}">
                                            <i class="fa fa-2x fa-bed" aria-hidden="true"></i>
                                            <span>
                                                {{ $room->title }} <br> {{ $room->price . ' ৳' }}
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endforeach
                        </div>
                    @endforeach
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
