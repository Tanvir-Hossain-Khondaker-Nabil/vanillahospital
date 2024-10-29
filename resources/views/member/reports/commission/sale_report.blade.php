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
        'name' => "Sale Commission Report",
    ],
];

$data['data'] = [
    'name' => "Sale Commission Report",
    'title'=> "Sale Commission Report",
    'heading' => "Sale Commission Report",
];
$total_commission  = 0;
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

                {!! Form::open(['route' => 'member.report.sale-commission','method' => 'GET', 'role'=>'form' ]) !!}

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
                                {{--<th><input type="checkbox" id="check-all" /></th>--}}
                                <th>SL#</th>
                                <th class="text-left" width="180px"> Employee Name</th>
                                <th class="text-left" width="180px"> Employee Designation</th>
                                <th class="text-right"  >Sales Amount</th>
                                <th class="text-right"  >Commission Amount</th>
                                <th class="text-right"  >Commission (%)</th>
                                <th class="text-right"  >Generate Date</th>
                            </tr>
                            @foreach($modal as $key => $value)
                                <tr>
                                    {{--<td><input type="checkbox" name="paid_check[]" value="{{$value->id}}" class="check-paid" /></td>--}}
                                    <td>{{ $key+1 }}</td>
                                    <td class="text-left">{{ $value->employee->employee_name_id }}</td>
                                    <td class="text-left">{{ $value->employee->designation->name }}</td>

                                    <td class="text-right">{{ create_money_format($value->total_sales_amount)  }}</td>
                                    <td class="text-right">{{ create_money_format($value->commission_amount)  }}</td>
                                    <td class="text-right">{{ create_money_format($value->commission_percentage)  }}</td>
                                    <td class="text-right">{{ db_date_month_year_format($value->generate_date)  }}</td>

                                </tr>

                                @php
                                    $total_commission += $value->commission_amount;
                                @endphp
                            @endforeach

                            <tr>
                                <th colspan="4" class="text-right">Total Commission</th>
                                <th class="text-right">{{ create_money_format($total_commission) }}</th>
                                <td colspan="2"></td>
                            </tr>
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

            $("#check-all").change( function () {

                if($(this).is(':checked') == true)
                {
                    $(".check-paid").prop('checked', true);
                }else{
                    $(".check-paid").prop('checked', false);
                }
            });

        });
    </script>

    @include('common.area_script')

@endpush
