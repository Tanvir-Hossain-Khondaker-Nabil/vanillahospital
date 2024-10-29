<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 07-Mar-20
 * Time: 12:25 PM
 */

$route = \Auth::user()->can(['member.technologists.index']) ? route('member.technologists.index') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Technologist',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Technologist',
    'title' => 'Technologist',
    'heading' => 'Technologist',
];

?>

@extends('layouts.back-end.master', $data)

@push('styles')
    <style>
        img {
            width: auto !important;
        }
    </style>
@endpush
@section('contents')
    <div class="row">
        <div class="col-md-12">

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Details</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-responsive table-striped">

                        <tr>
                            <th style="width: 145px">Technologist Name</th>
                            <td style=" width: 145px">
                                @if (@$technologists->technologistDoctor)
                                    <a
                                        href="{{ route('member.doctor_comission.show', $technologists->technologist_doctor_id) }}">{{ $technologists->technologistDoctor->name }}
                                    </a>
                                @else
                                    <a
                                        href="{{ route('member.employee.show', $technologists->technologist_employeeInfo_id) }}">{{ $technologists->technologistEmployee->first_name }}
                                        {{ $technologists->technologistEmployee->last_name }}</a>
                                @endif
                            </td>
                            <th style="width: 145px">Technologist Signature</th>
                            <td>
                                <img width="60" height="60"
                                    src="{{ asset('public/uploads/signature/' . $technologists->technologist_signature) }}">
                            </td>

                        </tr>

                        <tr>
                            <th style="width: 145px">Prepared By</th>
                            <td>
                                @if (@$technologists->preparedDoctor)
                                    <a
                                        href="{{ route('member.doctor_comission.show', $technologists->prepared_doctor_id) }}">{{ $technologists->preparedDoctor->name }}
                                    </a>
                                @else
                                    <a
                                        href="{{ route('member.employee.show', $technologists->prepared_employeeinfo_id) }}">{{ $technologists->preparedEmployee->first_name }}
                                        {{ $technologists->preparedEmployee->last_name }}</a>
                                @endif
                            </td>
                            <th style="width: 145px">Prepared By Signature</th>
                            <td>
                                <img width="60" height="60"
                                    src="{{ asset('public/uploads/signature/' . $technologists->prepared_by_signature) }}">

                            </td>

                        </tr>

                        <tr>
                            <th style="width: 145px">Checked By</th>
                            <td>
                                @if (@$technologists->checkedDoctor)
                                    <a
                                        href="{{ route('member.doctor_comission.show', $technologists->checked_by_doctor_id) }}">{{ $technologists->checkedDoctor->name }}
                                    </a>
                                @else
                                    <a
                                        href="{{ route('member.employee.show', $technologists->checked_by_employeeinfo_id) }}">{{ $technologists->checkedEmployee->first_name }}
                                        {{ $technologists->checkedEmployee->last_name }}</a>
                                @endif
                            </td>
                            <th style="width: 145px">Checked By Signature</th>
                            <td>
                                <img width="60" height="60"
                                    src="{{ asset('public/uploads/signature/' . $technologists->checked_by_signature) }}">

                            </td>
                        </tr>

                        <tr>
                            <th style="width: 145px">Specimen</th>
                            <td>
                                <p>{{ $technologists->specimen->specimen }}</p>

                            </td>
                            <th style="width: 145px">Test Group Name</th>
                            <td>
                                <p>{{ $technologists->testGroup->title }}</p>
                            </td>
                        </tr>

                    </table>
                </div>
                <!-- /.box-body -->
            </div>



            <div class="row">
                <div class="col-md-6">
                    <div class="box box-primary">

                        <!-- /.box-header -->
                        <div class="box-body ">
                            <table class="table table-responsive table-striped">

                                <tr>
                                    <th> SL </th>
                                    <th> Test Name </th>

                                </tr>

                                <tbody>
                                    @foreach ($technologists->assignTechnologist as $key => $row)
                                        <tr>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ @$row->subTestGroup->title }}</td>

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
