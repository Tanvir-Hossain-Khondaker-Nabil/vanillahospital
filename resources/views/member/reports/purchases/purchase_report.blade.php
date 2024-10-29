<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/23/2019
 * Time: 4:35 PM
 */

//  $route = \Auth::user()->can(['member.report.lost_profit']) ? route('member.report.lost_profit') : '#';

$home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';
$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'Reports',
        'href' => "#",
    ],
    [
        'name' => 'Purchase Report',
    ],
];

$data['data'] = [
    'name' => 'Purchase Report',
    'title'=> 'Purchase Report',
    'heading' =>trans('common.purchase_report'),
];

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
                    <h3 class="box-title">{{__('common.search')}}</h3>
                </div>

            {!! Form::open(['route' => 'member.report.purchase','method' => 'GET', 'role'=>'form' ]) !!}
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label> {{__('common.select_company')}}   </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>'Select Company']); !!}
                            </div>
                        @endif
                        <div class="col-md-3">
                            <label>{{__('common.from_date')}} </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-3">
                            <label>{{__('common.to_date')}}</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>

                        <div class="col-md-3 margin-top-23">
                            <label></label>
                            {{-- <input class="btn btn-info" value="Search" type="submit"/> --}}
                            <button class="btn btn-info"  type="submit">{{__('common.search')}}</button>
                            <a href="{{ route(Route::current()->getName()) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i>{{__('common.reload')}} </a>

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
                @include('member.reports.print_title_btn')

                <div class="box-body">
                    <div class="col-lg-12">
                        <table class="report-table table table-striped" id="dataTable">
                            <tbody>
                            <tr>
                                <th>#{{__("common.serial")}}</th>
                                <th>{{__("common.purchase_id")}} </th>
                                <th>{{__("common.date")}}</th>
                                <th class="text-left">{{__("common.supplier_name")}} </th>
                                <th>{{__("common.discount_type")}}</th>
                                <th class="text-right">{{__("common.total_discount")}}</th>
                                <th class="text-right">{{__("common.amount_to_pay")}} </th>
                                <th class="text-right">{{__("common.due_amount")}} </th>
                                <th class="text-right">{{__("common.paid_amount")}}</th>
                                <th class="text-right">{{__("common.total_price")}} </th>
                            </tr>
                            @foreach($purchases as $key => $value)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $value->id }}</td>
                                    <td>{{ db_date_month_year_format($value->date) }}</td>
                                    <td class="text-left">{{ $value->supplier ? $value->supplier->name : '' }}</td>
                                    <td class="text-right">{{ $value->discount_type == "Fixed" ? create_money_format($value->discount) : $value->discount."%" }}</td>
                                    <td class="text-right">{{ create_money_format($value->total_discount) }}</td>
                                    <td class="text-right">{{ create_money_format($value->amt_to_pay) }}</td>
                                    <td class="text-right">{{ create_money_format($value->due_amount) }}</td>
                                    <td class="text-right">{{ create_money_format($value->paid_amount) }}</td>
                                    <td class="text-right">{{ create_money_format($value->total_price) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 text-right">
                        {{ $purchases->links() }}
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
@endpush

