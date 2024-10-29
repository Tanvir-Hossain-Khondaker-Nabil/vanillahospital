<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 16-May-20
 * Time: 6:21 PM
 */

 $route = \Auth::user()->can(['member.report.list']) ? route('member.report.list'): '#';
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
        'name' => $report_title,
    ],
];

$data['data'] = [
    'name' => $report_title,
    'title'=> $report_title,
    'heading' =>trans("common.".strtolower(str_replace(' ', '_',trim($report_title)))) ,
];
$total_dr = 0;
$total_cr = 0;
$total_opening =0;
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
                    <h3 class="box-title">{{__('common.search')}} </h3>
                </div>

            {!! Form::open(['route' => 'member.report.sharer_balance_report','method' => 'GET', 'role'=>'form' ]) !!}
                <input name="customer_type" type="hidden" value="{{$customer_type ?? "" }}"/>
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if(Auth::user()->hasRole(['super-admin', 'developer']))
                                <div class="col-md-3">
                                    <label>{{__('common.select_company')}}    </label>
                                    {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('common.select_company')]); !!}
                                </div>
                            @endif
                            <div class="col-md-3">
                                <label> {{__('common.select_manager')}}     </label>
                                {!! Form::select('manager_id', $managers, null,['id'=>'manager_id','class'=>'form-control select2','placeholder'=>trans('common.select_manager'), 'required']); !!}
                            </div>
                            <div class="col-md-3">
                                <label>{{__('common.select_state')}}    </label>
                                {!! Form::select('division_id', $divisions, null,['id'=>'division_id','class'=>'form-control select2','placeholder'=>trans('common.select_state')]); !!}
                            </div>
                            <div class="col-md-3">
                                <label> {{__('common.select_city')}}  </label>
                                {!! Form::select('district_id', $districts, null,['id'=>'district_id','class'=>'form-control select2','placeholder'=>trans('common.select_city')]); !!}
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
                                <label>{{__('common.select_area')}}  </label>
                                {!! Form::select('area_id', $areas, null,['id'=>'area_id','class'=>'form-control select2','placeholder'=>trans('common.select_area')]); !!}
                            </div>
                            <div class="col-md-3">
                                <label>{{__('common.from_date')}}  </label>
                                <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                            </div>
                            <div class="col-md-3">
                                <label>{{__('common.to_date')}} </label>
                                <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                            </div>
                        </div>
                       <div class="col-md-12">
                           <div class="col-md-3 margin-top-23">
                               <input type="checkbox" name="opening_balance" value="1" {{ $opening_balance ? "checked" : "" }}/>
                               <label>{{__('common.with_opening_balance')}} </label>
                           </div>
                           <div class="col-md-4 margin-top-23">
                               <input type="checkbox" name="total_dr_Cr" value="1" {{ $total_dr_Cr ? "checked" : "" }} />
                               <label> {{__('common.transaction_total_debit')}} /{{__('common.credit_balance')}} </label>
                           </div>
                           <div class="col-md-2 margin-top-23">
                               <input type="checkbox" name="is_blacklist" value="1" {{ $is_blacklist ? "checked" : "" }}/>
                               <label>{{__('common.blocked_party')}}  </label>
                           </div>
                           <div class="col-md-3 margin-top-23">
                               <input type="checkbox" name="skip_blacklist" value="1" {{ $skip_blacklist ? "checked" : "" }}/>
                               <label>{{__('common.skip_blocked_party')}}   </label>
                           </div>
                       </div>

                        <div class="col-md-12 margin-top-23">
                            <label></label>
                            <input class="btn btn-info" value="{{__('common.search')}} " type="submit"/>
                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i>{{__('common.reload')}}  </a>

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
                    <h3 class="box-title">{{__("common.".strtolower(str_replace(' ', '_',trim($report_title)))) }}</h3>
                    <a href="{{  $full_url }}type=print" class="btn btn-sm btn-primary pull-right" id="btn-print"> <i class="fa fa-print"></i>{{__('common.print')}}   </a>
                    <a href="{{  $full_url }}type=excel" class="btn btn-sm btn-info pull-right mx-3"> <i class="fa fa-file-excel-o"></i>{{__('common.excel')}}   </a>
                    <a href="{{  $full_url }}type=download" class="btn btn-sm btn-success pull-right"> <i class="fa fa-download"></i>{{__('common.download')}}   </a>
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="table table-striped table-bordered" id="dataTable">

                            <tbody>
                            <tr>
                                <th rowspan="2">{{__('common.serial')}}#</th>
                                <th  rowspan="2">{{__('common.particulars')}} </th>
                                <th rowspan="2">{{__('common.phone')}} </th>
{{--                                <th rowspan="2"> Address</th>--}}
                                @if($opening_balance)<th class="text-right"  rowspan="2">{{__('common.opening_balance')}} </th>@endif
                                @if($total_dr_Cr)
                                    <th colspan="2" class="text-center">{{__('common.transactions')}} </th>
                                @endif
                                <th class="text-right"  rowspan="2">{{__('common.closing_balance')}} </th>
                                <th class="text-center"  rowspan="2">{{__('common.last_payment_date')}}  </th>
                            </tr>
                            <tr>
                                @if($total_dr_Cr)
                                    <th class="text-center">{{__('common.debit')}} </th>
                                    <th class="text-center">{{__('common.credit')}} </th>
                                @endif
                            </tr>
                            @php $i = 1; @endphp
                            @foreach($modal as $key => $value)
                                @if(
                                    $value->opening_balance>0
                                    || $value->total_dr > 0 || $value->total_cr  > 0
                                    || $value->closing_balance>0
                                )
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $value->name }}</td>
                                        <td>{{ $value->phone }}</td>
{{--                                        <td>{{ $value->address }}</td>--}}
                                        @if($opening_balance)<td class="text-right">{{ $value->opening_balance ? ($value->opening_balance > 0 ? create_money_format($value->opening_balance) . " Dr" : ($value->opening_balance < 0 ? create_money_format($value->opening_balance * (-1)) . " Cr" : "")) : "" }}</td>@endif
                                        @if($total_dr_Cr)
                                            <td class="text-right ">{{ $value->total_dr > 0 ? create_money_format($value->total_dr) . " Dr" : "" }}</td>
                                            <td class="text-right {{ $value->total_cr > 0 ? "font-italic" : "" }}">{{ $value->total_cr  > 0 ? create_money_format($value->total_cr) . " Cr" : "" }}</td>
                                        @endif
                                        <td class="text-right">{{ $value->closing_balance }}</td>
                                        <td class="text-center">{{ db_date_month_year_format($value->last_payment_date) }}</td>

                                        @php
                                            $i++;
                                        @endphp
                                    @endif
                                    </tr>
                                    @php
                                        $total_dr += $value->total_dr;
                                        $total_cr += $value->total_cr;
                                        $total_opening += $value->opening_balance;
                                    @endphp
                                    @endforeach
                                    <tr>
                                        @php
                                            $colspan=3;
                                        @endphp
                                        <th class="text-center" colspan="{{ $colspan }}">{{__('common.total')}}  </th>
                                        @if($opening_balance)<th class="text-right">{{ ($total_opening  > 0 ? create_money_format($total_opening)." Dr" : create_money_format( $total_opening*(-1) )." Cr")  }}</th>@endif
                                        @if($total_dr_Cr)
                                            <th class="text-right">{{ $total_dr  > 0 ? create_money_format($total_dr) . " Dr" : ""   }}</th>
                                            <th class="text-right">{{ $total_cr > 0 ? create_money_format($total_cr) . " Cr" : "" }}</th>
                                        @endif
                                        <th class="text-right">{{ $total_closing_balance<0 ? create_money_format($total_closing_balance*(-1))." Cr" : create_money_format($total_closing_balance)." Dr" }}</th>
                                        <td></td>
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
                        var html = "<option value=''>{{__('common.select_city')}}  </option>";

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
