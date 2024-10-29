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
        'name' => "Customer Sales Report",
    ],
];

$data['data'] = [
    'name' => "Customer Sales Report",
    'title'=> "Customer Sales Report",
    'heading' => "Customer Sales Report",
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

            {!! Form::open(['route' => 'member.report.sharer_sales_report','method' => 'GET', 'role'=>'form' ]) !!}
                <input name="customer_type" type="hidden" value="{{$customer_type ?? "" }}"/>
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
                            <div class="col-md-3">
                                <label>  Select Product </label>
                                {!! Form::select('product_id', $products, null,['id'=>'product_id','class'=>'form-control select2','placeholder'=>'Select Poduct', 'required']); !!}
                            </div>
                            <div class="col-md-3">
                                <label>  Select Manager </label>
                                {!! Form::select('manager_id', $managers, null,['id'=>'manager_id','class'=>'form-control select2','placeholder'=>'Select Manager']); !!}
                            </div>
                            <div class="col-md-3">
                                <label>  Select State </label>
                                {!! Form::select('division_id', $divisions, null,['id'=>'division_id','class'=>'form-control select2','placeholder'=>'Select State']); !!}
                            </div>
                            <div class="col-md-3">
                                <label>  Select City </label>
                                {!! Form::select('district_id', $districts, null,['id'=>'district_id','class'=>'form-control select2','placeholder'=>'Select City']); !!}
                            </div>
                            {{-- <div class="col-md-3">
                                <label>  Select Upazilla </label>
                                {!! Form::select('upazilla_id', $upazillas, null,['id'=>'upazilla_id','class'=>'form-control select2','placeholder'=>'Select Upazilla']); !!}
                            </div>
                            <div class="col-md-3">
                                <label>  Select Union </label>
                                {!! Form::select('union_id', $unions, null,['id'=>'union_id','class'=>'form-control select2','placeholder'=>'Select Union']); !!}
                            </div> --}}
                            <div class="col-md-3">
                                <label>  Select Area </label>
                                {!! Form::select('area_id', $areas, null,['id'=>'area_id','class'=>'form-control select2','placeholder'=>'Select Area']); !!}
                            </div>
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
                    <a href="{{  $full_url }}type=excel" class="btn btn-sm btn-info pull-right mx-3"> <i class="fa fa-file-excel-o"></i> Excel </a>
                    <a href="{{  $full_url }}type=download" class="btn btn-sm btn-success pull-right"> <i class="fa fa-download"></i> Download </a>
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="table table-striped table-bordered" id="dataTable">

                            <tbody>
                            <tr>
                                <th>SL#</th>
                                <th>Customer Name</th>
                                {{--<th >Phone</th>--}}
                                <th class="text-center" width="180px"> Product Name</th>
                                <th class="text-right"  >Opening Balance</th>
                                <th class="text-center"> Total Sales Qty</th>
                                <th class="text-center"> Average Price</th>
                                <th class="text-center"> Total Sales Price</th>
                                <th class="text-right"  >Collection Balance</th>
                                {{--<th class="text-right"  >Closing Balance</th>--}}
                                <th class="text-center" >Last Payment Date </th>
                            </tr>
                            @php $i = 1; @endphp
                            @foreach($modal as $key => $value)

                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $value->name }}</td>
                                        {{--<td>{{ $value->phone }}</td>--}}
{{--                                        <td>{{ $value->address }}</td>--}}


                                        <td class="text-right">{{ $value->item_name }}</td>
                                        <td class="text-right">{{ $value->opening_balance ? ($value->opening_balance > 0 ? create_money_format($value->opening_balance) . " Dr" : ($value->opening_balance < 0 ? create_money_format($value->opening_balance * (-1)) . " Cr" : "")) : "" }}</td>

                                        <td class="text-right">{{ $value->item_total_qty }}</td>
                                        <td class="text-right">{{ $value->item_price ? create_money_format($value->item_price) : ''  }}</td>
                                        <td class="text-right">{{ $value->item_total_price ? create_money_format($value->item_total_price) : '' }}</td>

                                        <td class="text-right">{{ $value->total_dr ? create_money_format($value->total_dr) : '' }}</td>
{{--                                        <td class="text-right">{{ $value->closing_balance }}</td>--}}

                                        <td class="text-center">{{ db_date_month_year_format($value->last_payment_date) }}</td>

                                        @php
                                            $i++;
                                        @endphp
                                    </tr>
                                    @php
                                        $total_dr += $value->total_dr;
                                        $total_qty += $value->item_total_qty;
                                        $total_item_price += $value->item_total_price;
                                        $total_opening += $value->opening_balance;
                                    @endphp
                                    @endforeach
                                    <tr>

                                        <th class="text-center" colspan="3"> Total </th>
                                        <th class="text-right">{{ ($total_opening  > 0 ? create_money_format($total_opening)." Dr" : create_money_format( $total_opening*(-1) )." Cr")  }}</th>

                                        <th class="text-center" > {{ $total_qty }} </th>
                                        <th></th>
                                        <th class="text-center" > {{ create_money_format($total_item_price) }} </th>
                                        <th class="text-center" > {{ create_money_format($total_dr) }} </th>
                                        {{--<th class="text-right" >{{ $total_closing_balance<0 ? create_money_format($total_closing_balance*(-1))." Cr" : create_money_format($total_closing_balance)." Dr" }}</th>--}}
                                        {{--<td></td>--}}
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
