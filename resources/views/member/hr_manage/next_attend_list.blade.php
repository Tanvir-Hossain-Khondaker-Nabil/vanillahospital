<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

$route =  \Auth::user()->can(['member.attendances.index']) ? route('member.attendances.index') : "#";
$home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Next Attend Employee List',
//        'href' => $route,
    ],
//    [
//        'name' => 'Create',
//    ],
];

$data['data'] = [
    'name' => 'Next Attend Employee List',
    'title'=>'Next Attend Employee List',
    'heading' => 'Next Attend Employee List',
];

?>

@extends('layouts.back-end.master', $data)


@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">

            @include('common._alert')

            @include('common._error')

            <div class="box box-primary">

                @include('member.reports.print_title_btn')

                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">
                            <table class="table table-responsive" id="myTable">
                                <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($next_attends as $key => $value)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value->employee->employeeID }}</td>
                                        <td>{{ $value->employee->uc_full_name }}</td>
                                        <td>{{ $value->employee->phone2 }}</td>
                                        <td>{{ $value->start_date ? create_date_format($value->start_date,'/') : '' }}</td>
                                        <td>{{ $value->end_date ? create_date_format($value->end_date,'/') : '' }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
@endsection


@push('scripts')

    <!-- Date range picker -->
    <script type="text/javascript">

        $(function () {
            $('#myTable').DataTable();
        });

    </script>

@endpush

