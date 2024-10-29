<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 16-May-20
 * Time: 6:21 PM
 */

 $route = \Auth::user()->can(['member.report.list']) ? route('member.report.list') : '#';
$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Reports',
        'href' => $route,
    ],
    [
        'name' => "Attendance Report",
    ],
];

$data['data'] = [
    'name' => "Attendance Report",
    'title'=> "Attendance Report",
    'heading' => "Attendance Report",
];
$total  = 0;
?>
@extends('layouts.back-end.master', $data)

@push('styles')
    <link rel="stylesheet" href="{{ asset('public/adminLTE/bower_components/select2/dist/css/select2.min.css')}}">
@endpush

@section('contents')

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <h3 class="box-title">Search</h3>
                </div>

                {!! Form::open(['route' => 'member.report.employee-attendance','method' => 'GET', 'role'=>'form' ]) !!}

                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if(Auth::user()->hasRole(['super-admin', 'developer']))
                                <div class="col-md-3">
                                    <label>  Select Company </label>
                                    {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}
                                </div>
                            @endif

                            @if(Auth::user()->hasRole(['admin']))
                                <div class="col-md-3">
                                    <label>  Select Designation </label>
                                    {!! Form::select('designation_id', $designations, null,['id'=>'designation_id','class'=>'form-control select2','placeholder'=>'Select Designation']); !!}
                                </div>
                                <div class="col-md-3">
                                    <label>  Select Employee </label>
                                    {!! Form::select('employee_id', $employees, null,['id'=>'employee_id','class'=>'form-control select2','placeholder'=>'Select Employee']); !!}
                                </div>
                                {{-- <div class="col-md-3">
                                    <label>  Select Region </label>
                                    {!! Form::select('region_id', $regions, null,['id'=>'region_id','class'=>'form-control select2 select-region-district','placeholder'=>'Select Region']); !!}
                                </div> --}}
                                <div class="col-md-3">
                                    <label>  Select City </label>
                                    {!! Form::select('district_id', $districts, null,['id'=>'district_id','class'=>'form-control select2 select-district-thana','placeholder'=>'Select City']); !!}
                                </div>
                                {{-- <div class="col-md-3 mt-3">
                                    <label>  Select Thana </label>
                                    {!! Form::select('thana_id', $thanas, null,['id'=>'thana_id','class'=>'form-control select2','placeholder'=>'Select thana']); !!}
                                </div> --}}
                                <div class="col-md-3 mt-3">
                                    <label>  Select Area </label>
                                    {!! Form::select('area_id', $areas, null,['id'=>'area_id','class'=>'form-control select2','placeholder'=>'Select area']); !!}
                                </div>
                            @endif

                            <div class="col-md-3 mt-3">
                                <label> From Date </label>
                                <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                            </div>
                            <div class="col-md-3 mt-3">
                                <label> To Date</label>
                                <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                            </div>
                        </div>


                        <div class="col-md-12 margin-top-23">
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

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{!! $report_title !!} </h3>
                    <a href="{{  $full_url }}type=print" class="btn btn-sm btn-primary pull-right" id="btn-print"> <i class="fa fa-print"></i> Print </a>
                    {{-- <a href="{{  $full_url }}type=excel" class="btn btn-sm btn-info pull-right mx-3"> <i class="fa fa-file-excel-o"></i> Excel </a> --}}
                    <a href="{{  $full_url }}type=download" class="btn btn-sm btn-success pull-right"> <i class="fa fa-download"></i> Download </a>
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="table table-striped table-bordered" id="dataTable">

                            <tbody>
                            <tr>
                                <th>SL#</th>
                                <th class="text-left" width="180px"> Employee Name</th>
                                <th class="text-left" width="180px"> Employee Designation</th>
                                <th class="text-right"  > Date</th>
                                <th class="text-right"  >In Time</th>
                            </tr>
                            @foreach($modal as $key => $value)
                                @php
                                   $date = \Carbon\Carbon::create()->day($key+1)->month($start_month);

                                @endphp

                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td class="text-left">{{ $value->employee->uc_full_name }}</td>
                                    <td class="text-left">{{ $value->employee->designation->name }}</td>

                                    <td class="text-right">{{ db_date_month_year_format($value->visit_date)  }}</td>
                                    <td class="text-right">{{ $value->in_time  }}</td>

                                </tr>

                            @endforeach

                        @if(count($modal)>0 )
                            @if($total_days>0 )
                            <tr>
                                <th colspan="4" class="text-right"> Monthly Days  </th>
                                <th class="text-right">{{ $total_days }}</th>
                            </tr>
                            @endif
                            <tr>
                                <th colspan="4" class="text-right"> Total Attend </th>
                                <th class="text-right">{{ count($modal) }}</th>
                            </tr>
                        @else
                                <tr>
                                    <th colspan="5" class="text-center"> No Data Found </th>
                                </tr>
                        @endif
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection



@push('scripts')

    <script src="{{ asset('public/adminLTE/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script type="text/javascript">

        $(document).ready( function(){
            $('.select2').select2();
            $('.date').datepicker({
                "setDate": new Date(),
                "format": 'mm/dd/yyyy',
                "endDate": "+0d",
                "todayHighlight": true,
                "autoclose": true
            });

        });
    </script>

    @include('common.area_script')

@endpush
