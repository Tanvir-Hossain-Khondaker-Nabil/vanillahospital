<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */


 $route =  \Auth::user()->can(['member.requisition_expenses.index']) ? route('member.requisition_expenses.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Requisition Expenses ',
        'href' => $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Requisition Expenses ',
    'title'=>'List Of Requisition Expenses ',
    'heading' =>trans('common.list_of_requisition_expenses'),
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

                    @if(\Auth::user()->can(['member.requisition_expenses.create']))
                    <div class="box-header">
                        <a href="{{ route('member.requisition_expenses.create') }}" class="btn btn-info"> <i class="fa fa-plus"> {{__('common.add_requisition_expense')}}  </i></a>
                    </div>

                    @endif
                    <!-- END SAMPLE FORM PORTLET-->
                </div>
                <div class="box-body">
                    @if(!Auth::user()->hasRole(['user']))
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 px-0">

                        {!! Form::open(['route' => 'member.requisition_expenses.index','method' => 'GET', 'role'=>'form' ]) !!}
                        <div class="col-md-3  pl-0">
                            <div class="form-group">
                                <label for="status">{{__('common.select_employee')}} </label>
                                <select class="form-control select2" name="employee_id" id="employee_id">
                                    <option value=""> {{__('common.select_employee')}}</option>
                                    @foreach ($employees as $key => $value)
                                        <option value="{{ $value->id }}"    >
                                            {{ $value->employee_name_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">{{__('common.date')}} </label>
                                {!! Form::text('date', null, [
                                    'class' => 'form-control date',
                                    'placeholder' =>trans('common.enter_date'),
                                    'autocomplete' => 'off',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-3">
                            {{-- <input class="btn btn-info btn-search" value="Search" type="submit"/> --}}
                            <button class="btn btn-info btn-search" type="submit">{{__('common.search')}}</button>
                            <a class="btn btn-primary btn-search" href="{{ route(Route::current()->getName()) }}"> <i class="fa fa-refresh"></i> </a>
                        </div>

                        {!! Form::close() !!}
                    </div>
                    @endif



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
