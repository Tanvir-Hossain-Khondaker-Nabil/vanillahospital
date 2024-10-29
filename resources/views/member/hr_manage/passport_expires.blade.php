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
        'name' => 'Passport Expires',
//        'href' => $route,
    ],
//    [
//        'name' => 'Create',
//    ],
];

$data['data'] = [
    'name' => 'Passport Expires',
    'title'=>'Passport Expires',
    'heading' =>trans('common.passport_expires'),
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

        @include('common._alert')

        @include('common._error')

            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">{{__('common.search')}} </h3>
                </div>
                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12 px-0">

                        {!! Form::open(['route' => Route::current()->getName(),'method' => 'GET', 'role'=>'form' ]) !!}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">{{__('common.select_country')}}  </label>
                                    <select class="form-control select2"  name="country_id"
                                            id="country_id">
                                        <option value=""> {{__('common.select_country')}}</option>
                                        @foreach ($countries as $key => $value)
                                            <option value="{{ $key }}"   {{ request()->get('country_id') == $key ? "selected" : "" }}  >
                                                {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status"> {{__('common.select_department')}}  </label>
                                    <select class="form-control select2" onchange="selectDepartment(this)" name="department_id"
                                            id="department_id">
                                        <option value=""> {{__('common.select_department')}}</option>
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
                                        <option value=""> {{__('common.select_designation')}}</option>
                                        @foreach ($designations as $value)
                                            <option value="{{ $value->id }}"  {{ request()->get('designation_id') == $value->id ? "selected" : "" }} >
                                                {{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name"> {{__('common.month')}}   </label>
                                    <select class="form-control select2" name="month" id="month"  >
                                        <option value="">{{__('common.select_month')}}  </option>
                                        @foreach(get_months() as $key => $value)
                                            <option value="{{$key}}"  {{ (request()->get('month') != "") && request()->get('month') == $key ? "selected" : "" }}  > {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name"> {{__('common.year')}}  </label>
                                    <select class="form-control select2" name="year"  id="year" >
                                        <option value=""> {{__('common.select_year')}} </option>
                                        @foreach($years as $value)
                                            <option value="{{$value}}"  {{ request()->get('year') == $value ? "selected" : "" }}  > {{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="name">{{__('common.expire_date')}}  ({{__('common.before')}} )   </label>
                                    {!! Form::text('expire_date', request()->get('expire_date') != "" ? request()->get('expire_date') : null, [
                                        'class' => 'form-control expire_date',
                                        'placeholder' =>trans('common.expire_date'),
                                        'autocomplete' => 'off',
                                    ]) !!}
                                </div>
                            </div>

                            <div class="col-md-3">
                                <input class="btn btn-info btn-search" value="{{__('common.search')}}" type="submit"/>
                                <a class="btn btn-primary btn-search" href="{{ route(Route::current()->getName()) }}"> <i class="fa fa-refresh"></i> </a>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>

            </div>

            <div class="box box-primary">
                @include('member.reports.print_title_btn')

                <div class="box-body">
                    <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                        <!-- BEGIN SAMPLE FORM PORTLET-->

                        <div class="table-responsive">
                            <table class="table table-responsive" id="myTable">
                                <thead>
                                <tr>

                                    <th>#{{__('common.serial')}}</th>
                                    <th>{{__('common.employee_id')}}</th>
                                    <th>{{__('common.name')}}</th>
                                    <th>{{__('common.phone')}}</th>
                                    <th>{{__('common.designation')}}</th>
                                    <th>{{__('common.expire_date')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($passport_expires as $key => $value)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $value->employeeID }}</td>
                                        <td>{{ $value->uc_full_name }}</td>
                                        <td>{{ $value->phone2 }}</td>
                                        <td>{{ $value->designation->name }}</td>
                                        <td>{{ $value->passport_expire ? create_date_format($value->passport_expire,'/') : '' }}</td>
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

    <!-- Date range picker -->
    <script type="text/javascript">

        $(function () {
            $('#myTable').DataTable();
            $('.select2').select2();

            $("#month").change( function(){

                if($(this).val() == ""){
                    $("#year").attr("required", false);
                    $("#year").val("").trigger('change');
                }else{
                    $("#year").attr("required", true);
                }
            });

            $('.expire_date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "todayHighlight": true,
                "autoclose": true
            });
        });

    </script>

@endpush

