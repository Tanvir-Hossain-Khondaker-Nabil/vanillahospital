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
        'name' => "Summary Requisition Report",
    ],
];

$data['data'] = [
    'name' => "Summary Requisition Report",
    'title'=> "Summary Requisition Report",
    'heading' => "Summary Requisition Report",
];
$total_opening = $total_qty = $total_item_price = 0;
$total_dr = 0;
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

            {!! Form::open(['route' => 'member.report.requistion_report','method' => 'GET', 'role'=>'form' ]) !!}

            <!-- /.box-header -->
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
                                    <label>  Select Dealer </label>
                                    {!! Form::select('dealer_id', $dealers, null,['id'=>'dealer_id','class'=>'form-control select2','placeholder'=>'Select dealer']); !!}
                                </div>
                            @endif
                            <div class="col-md-3">
                                <label>  Select Product </label>
                                {!! Form::select('product_id', $products, null,['id'=>'product_id','class'=>'form-control select2','placeholder'=>'Select Product']); !!}
                            </div>
                            {{-- <div class="col-md-3">
                                <label>  Select Manager </label>
                                {!! Form::select('manager_id', $managers, null,['id'=>'manager_id','class'=>'form-control select2','placeholder'=>'Select Manager']); !!}
                            </div> --}}
                            {{-- <div class="col-md-3">
                                <label>  Select Division </label>
                                {!! Form::select('division_id', $divisions, null,['id'=>'division_id','class'=>'form-control select2','placeholder'=>'Select Division']); !!}
                            </div>
                            <div class="col-md-3">
                                <label>  Select District </label>
                                {!! Form::select('district_id', $districts, null,['id'=>'district_id','class'=>'form-control select2','placeholder'=>'Select District']); !!}
                            </div>
                            <div class="col-md-3">
                                <label>  Select Upazilla </label>
                                {!! Form::select('upazilla_id', $upazillas, null,['id'=>'upazilla_id','class'=>'form-control select2','placeholder'=>'Select Upazilla']); !!}
                            </div>
                            <div class="col-md-3">
                                <label>  Select Union </label>
                                {!! Form::select('union_id', $unions, null,['id'=>'union_id','class'=>'form-control select2','placeholder'=>'Select Union']); !!}
                            </div> --}}
                            {{-- <div class="col-md-3">
                                <label>  Select Area </label>
                                {!! Form::select('area_id', $areas, null,['id'=>'area_id','class'=>'form-control select2','placeholder'=>'Select Area']); !!}
                            </div> --}}
                            <div class="col-md-3">
                                <label> From Date </label>
                                <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                            </div>
                            <div class="col-md-3">
                                <label> To Date</label>
                                <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                            </div>
                        </div>
                       <div class="col-md-12">
                           {{--<div class="col-md-2 margin-top-23">--}}
                               {{--<input type="checkbox" name="is_blacklist" value="1" {{ $is_blacklist ? "checked" : "" }}/>--}}
                               {{--<label> Blocked Party </label>--}}
                           {{--</div>--}}
                           {{--<div class="col-md-3 margin-top-23">--}}
                               {{--<input type="checkbox" name="skip_blacklist" value="1" {{ $skip_blacklist ? "checked" : "" }}/>--}}
                               {{--<label> Skip Blocked Party </label>--}}
                           {{--</div>--}}
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
                                <th class="text-left" width="180px"> Product Name</th>
                                <th class="text-right"  >Purchase Requisition</th>
                                <th class="text-right"  >Purchase Amount</th>
                                <th class="text-right"  >Sale Requisition</th>
                                <th class="text-right"  >Sale Amount</th>
                                <th class="text-right"  >Net Profit/Loss</th>
                            </tr>
                            @php $i = 1; @endphp
                            @foreach($modal as $key => $value)

                            @php
                                $profit = $value->p_req_total-$value->p_total+$value->s_req_total-$value->s_total;
                            @endphp
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td class="text-left">{{ $value->item_name }}</td>
                                        <td class="text-right">{{ create_money_format($value->p_req_total) }}</td>
                                        <td class="text-right">{{ create_money_format($value->p_total) }}</td>
                                        <td class="text-right">{{ create_money_format($value->s_req_total) }}</td>
                                        <td class="text-right">{{ create_money_format($value->s_total) }}</td>
                                        <td class="text-right">{{ create_money_format($profit) }}</td>

                                        @php
                                            $i++;
                                            $total_dr += $profit;
                                        @endphp
                                    </tr>
                                    @php
                                    @endphp
                                    @endforeach
                                    <tr>

                                        <th class="text-right" colspan="6"> Gross Profit/Loss </th>
                                         <th class="text-center" > {{ create_money_format($total_dr) }} </th>

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
