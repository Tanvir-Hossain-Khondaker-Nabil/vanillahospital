<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 1/1/2018
 * Time: 11:37 PM
 */


 $route =  \Auth::user()->can(['member.employee.index']) ? route('member.employee.index') : "#";
 $home1 =  \Auth::user()->can(['admin.dashboard']) ? route('admin.dashboard') : "#";

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Employee',
        'href' =>  $route,
    ],
    [
        'name' => 'Index',
    ],
];

$data['data'] = [
    'name' => 'Employee',
    'title'=>'List Of Employee',
    'heading' =>trans('common.list_of_employee'),
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

                    @if(\Auth::user()->can(['member.employee.create']))
                    <div class="box-header">
                        <a href="{{ route('member.employee.create') }}" class="btn btn-info"> <i class="fa fa-plus">{{__('common.add_employee')}} </i></a>
                    </div>
                    @endif

                    <!-- END SAMPLE FORM PORTLET-->
                </div>
                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 px-0">

                    {!! Form::open(['route' => 'member.employee.index','method' => 'GET', 'role'=>'form' ]) !!}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">{{__('common.select_country')}}  </label>
                                <select class="form-control select2" name="country_id"
                                        id="country_id">
                                    <option value="">{{__('common.select_country')}}</option>
                                    @foreach ($countries as $key => $value)
                                        <option value="{{ $key }}"   {{ request()->get('country_id') == $key ? "selected" : "" }}  >
                                            {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">{{__('common.select_department')}}  </label>
                                <select class="form-control select2" onchange="selectDepartment(this)" name="department_id"
                                        id="department_id">
                                    <option value="">{{__('common.select_department')}}</option>
                                    @foreach ($departments as $value)
                                        <option value="{{ $value->id }}"   {{ request()->get('department_id') == $value->id ? "selected" : "" }}  >
                                            {{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="status">{{__('common.select_designation')}}</label>
                                <select class="form-control select2" name="designation_id" id="designation_id">
                                    <option value="">{{__('common.select_designation')}}</option>
                                    @foreach ($designations as $value)
                                        <option value="{{ $value->id }}"  {{ request()->get('designation_id') == $value->id ? "selected" : "" }} >
                                            {{ $value->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">{{__('common.month')}}  </label>
                                <select class="form-control select2" name="month" id="month"  >
                                    <option value="">{{__('common.select_month')}}  </option>
                                    @foreach(get_months() as $key => $value)
                                        <option value="{{$key}}"  {{ request()->get('month')  != "" && request()->get('month') == $key ? "selected" : "" }}  > {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">{{__('common.join_year')}}   </label>
                                <select class="form-control select2" name="join_year"  >
                                    <option value="">{{__('common.select_join_year')}}   </option>
                                    @foreach($join_years as $value)
                                        <option value="{{$value}}"  {{ request()->get('join_year') == $value ? "selected" : "" }}  > {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="name">{{__('common.birth_year')}}  </label>
                                <select class="form-control select2" name="dob_year" >
                                    <option value="">{{__('common.select_birth_year')}} </option>
                                    @foreach($dob_years as $value)
                                        <option value="{{$value}}" {{ request()->get('dob_year') == $value ? "selected" : "" }} > {{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input class="btn btn-info btn-search" value="{{__('common.search')}}" type="submit"/>
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
        });
    </script>
@endpush
