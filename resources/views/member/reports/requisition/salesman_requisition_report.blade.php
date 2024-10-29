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
        'name' => "Salesman Requisition Report",
    ],
];

$data['data'] = [
    'name' => "Salesman Requisition Report",
    'title'=> "Salesman Requisition Report",
    'heading' => "Salesman Requisition Report",
];
 $sales_total = $req_total = 0;
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

            {!! Form::open(['route' => 'member.report.salesman_requistion_report','method' => 'GET', 'role'=>'form' ]) !!}

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
                                    <label>  Select Salesman </label>
                                    {!! Form::select('user_id', $salesmanList, null,['id'=>'user_id','class'=>'form-control select2','placeholder'=>'Select Salesman']); !!}
                                </div>
                            @endif

                            <div class="col-md-3">
                                <label> From Date </label>
                                <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                            </div>
                            <div class="col-md-3">
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
                                <th class="text-left" width="180px"> Req.Inv Date</th>
                                <th class="text-right"  >Req.Inv No</th>
                                <th class="text-right"  >Req. Amount</th>
                                <th class="text-right"  >Dealer</th>
                                <th class="text-right"  >Sales Date</th>
                                <th class="text-right"  >Sales Inv No</th>
                                <th class="text-right"  >Sales Amount</th>

                                @foreach($modal as $key => $value)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td class="text-left">{{ create_date_format($value->date, '/') }}</td>
                                        <td class="text-right">{{ $value->id }}</td>
                                        <td class="text-right">{{ create_money_format($value->total_price) }}</td>
                                        <td class="text-right">{{ $value->dealer ? $value->dealer->name : "" }}</td>
                                        <td class="text-right">{{ $value->sales ? create_date_format($value->sales->date) : ""}}</td>
                                        <td class="text-right">{{ $value->sales ? create_money_format($value->sales->id) : "" }}</td>
                                        <td class="text-right">{{ $value->sales ? create_money_format($value->sales->total_price) : "" }}</td>

                                    </tr>

                                    @php
                                        $req_total += $value->total_price;
                                        $sales_total += $value->sales ? $value->sales->total_price : 0;
                                    @endphp
                            @endforeach
                                    <tr>

                                        <th class="text-right" colspan="3"> Total </th>
                                         <th class="text-center" colspan="2"> {{ create_money_format($req_total) }} </th>
                                        <th class="text-right" colspan="2"> Total </th>
                                         <th class="text-center" > {{ create_money_format($sales_total) }} </th>

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

            $("#division_id").on('change',function(e){
                e.preventDefault();
                var url = "{{ route('search.select_district') }}";
                var id = $(this).val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'id' : id
                };
                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function(data) {
                    console.log(data);
                    if(data.status == "success")
                    {
                        var html = "<option value=''>Select City </option>";

                        $.each( data.modals, function( key, value ) {
                            html += "<option value='"+key+"'>"+value+"</option>";
                        });

                        $('#district_id').html(html);
                    }else{
//                        console.log(data);
                        bootbox.alert("No data Found!! ");
                    }
                });
            });
            $("#district_id").on('change',function(e){
                e.preventDefault();
                var url = "{{ route('search.select_upazilla') }}";
                var id = $(this).val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'id' : id
                };
                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function(data) {
                    if(data.status == "success")
                    {
                        var html = "<option value=''>Select upazilla </option>";

                        $.each( data.modals, function( key, value ) {
                            html += "<option value='"+key+"'>"+value+"</option>";
                        });

                        $('#upazilla_id').html(html);
                    }else{
//                        console.log(data);
                        bootbox.alert("No data Found!! ");
                    }
                });
            });
            $("#upazilla_id").on('change',function(e){
                e.preventDefault();
                var url = "{{ route('search.select_union') }}";
                var id = $(this).val();

                var form_data = {
                    '_token' : "{{ csrf_token() }}",
                    'id' : id
                };
                $.ajax({
                    type        : 'POST',
                    url         : url, // the url where we want to POST
                    data        : form_data,
                    dataType    : 'json',
                    encode      : true
                }).done(function(data) {
                    if(data.status == "success")
                    {
                        var html = "<option value=''>Select Union </option>";

                        $.each( data.unions, function( key, value ) {
                            html += "<option value='"+key+"'>"+value+"</option>";
                        });
                        $('#union_id').html(html);
                        var html = "<option value=''>Select Area </option>";

                        $.each( data.areas, function( key, value ) {
                            html += "<option value='"+key+"'>"+value+"</option>";
                        });

                        $('#area_id').html(html);
                    }else{
                        bootbox.alert("No data Found!! ");
                    }
                });
            });
        });
    </script>
@endpush
