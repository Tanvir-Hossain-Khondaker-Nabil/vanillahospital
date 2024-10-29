<?php
/**
 * Created by PhpStorm.
 * User: R-Creation
 * Date: 2/27/2019
 * Time: 12:52 PM
 */

$route =  \Auth::user()->can(['member.project_expenses.index']) ? route('member.project_expenses.index') : "#";
$home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Project Expense Report',
//        'href' => $route,
    ],
//    [
//        'name' => 'Create',
//    ],
];

$data['data'] = [
    'name' => 'Project Expense Report',
    'title'=>'Project Expense Report',
    'heading' => 'Project Expense Report',
];

?>

@extends('layouts.back-end.master', $data)


@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
@endpush

@section('contents')
    <div class="row">
        <!-- left column -->
        <div class="col-md-12">


            <div class="box box-primary">
                <div class="box box-default">
                    <div class="box-header with-border">
                        <h3 class="box-title">Search</h3>
                    </div>

                    {!! Form::open(['route' => Route::current()->getName(),'method' => 'GET', 'role'=>'form' ]) !!}
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3">
                                <label>Project </label>
                                {!! Form::select('project_id', $projects, null,['id'=>'project_id','class'=>'form-control select2','placeholder'=>'Select Project']); !!}
                            </div>
                            <div class="col-md-3">
                                <label>Project Expense Type </label>
                                {!! Form::select('expense_type_id', $project_expense_types, null,['id'=>'expense_type_id','class'=>'form-control select2','placeholder'=>'Select Project expense type']); !!}
                            </div>

                            <div class="col-md-3">
                                <label> From Date </label>
                                <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                            </div>
                            <div class="col-md-3">
                                <label> To Date</label>
                                <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                            </div>

                            <div class="col-md-3 margin-top-23">
                                <label></label>
                                <input class="btn btn-info" value="Search" type="submit"/>
                                <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> Reload</a>

                            </div>
                        </div>
                        <!-- /.row -->
                    </div>
                    {{--<div class="box-body">--}}

                    {{----}}
                    {{--</div>--}}

                    {!! Form::close() !!}
                </div>

                @include('member.reports.print_title_btn')

                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">
                            <table class="table table-responsive" id="myTable">
                                <thead>
                                <tr>
                                    <th>#SL</th>
                                    <th>Date</th>
                                    <th>Code</th>
                                    <th>Project</th>
                                    <th>Expense Name</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($project_expenses as $key => $value)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value->project_expense->date_format }}</td>
                                        <td>{{ $value->project_expense->code }}</td>
                                        <td>{{ $value->project_expense->project->project }}</td>
                                        <td>{{ $value->projectExpenseType->display_name }}</td>
                                        <td>{{ $value->amount }}</td>
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


    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            $('.select2').select2();
            $('#myTable').DataTable();
            $('.date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "todayHighlight": true,
                "autoclose": true
            });
        });
    </script>
@endpush

