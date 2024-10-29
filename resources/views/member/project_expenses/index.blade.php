<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
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
        'name' => 'Project Expenses ',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Project Expenses ',
    'title'=>'List Of Project Expenses ',
    'heading' => 'List Of Project Expenses ',
];

?>

@extends('layouts.back-end.master', $data)

@section('contents')


    @push('styles')
        <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css') }}">
    @endpush

    <div class="row">
        <div class="col-xs-12">

            @include('common._alert')

            <div class="box">
                <div class="portlet light">

                    @if(\Auth::user()->can(['member.project_expenses.create']))
                    <div class="box-header">
                        <a href="{{ route('member.project_expenses.create') }}" class="btn btn-info"> <i class="fa fa-plus"> Add Project Expense  </i></a>
                    </div>

                    @endif
                    <!-- END SAMPLE FORM PORTLET-->
                </div>
                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 px-0">

                        {!! Form::open(['route' => 'member.project_expenses.index','method' => 'GET', 'role'=>'form' ]) !!}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">Select Project </label>
                                <select class="form-control select2" name="project_id" id="project_id">
                                    <option value=""> Select Project</option>
                                    @foreach ($projects as $key => $value)
                                        <option value="{{ $key }}"    >
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name"> Date </label>
                                {!! Form::text('date', null, [
                                    'class' => 'form-control date',
                                    'placeholder' => 'Enter Date',
                                    'autocomplete' => 'off',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            <input class="btn btn-info btn-search" value="Search" type="submit"/>
                            <a class="btn btn-primary btn-search" href="{{ route(Route::current()->getName()) }}"> <i class="fa fa-refresh"></i> </a>
                        </div>

                        {!! Form::close() !!}
                    </div>



                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">
                            {!! $dataTable->table() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.1.0/css/buttons.dataTables.min.css">
    <script src="https://cdn.datatables.net/buttons/1.1.0/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('public/vendor/datatables/buttons.server-side.js') }}"></script>
    {!! $dataTable->scripts() !!}
    <script type="text/javascript">
        $(function () {
            $('.select2').select2();
            $('.date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "todayHighlight": true,
                "autoclose": true
            });
        });
    </script>
@endpush
