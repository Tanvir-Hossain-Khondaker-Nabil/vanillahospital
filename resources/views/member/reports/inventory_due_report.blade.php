<?php
/**
 * Created by PhpStorm.
 * User: Mobarok Hossen
 * Date: 7/6/2019
 * Time: 4:16 PM
 */

$cus_type = ucfirst($type);

$route = \Auth::user()->can(['member.report.inventory_due']) ? route('member.report.inventory_due',$type): '#';
 $home1 = \Auth::user()->can(['member.dashboard']) ? route('member.dashboard') : '#';

$data['breadcrumb'] = [
    [
        'name' => 'Home',
        'href' => $home1,
        'icon' => 'fa fa-home',
    ],
    [
        'name' => 'List',
        'href' => $route,
    ],
    [
        'name' => $cus_type.' Due Report',
    ],
];

$data['data'] = [
    'name' => $cus_type.' Due Report',
    'title'=> $cus_type.' Due Report',
    // 'heading' => $cus_type.' Due Report',
    'heading' => trans('common.'.strtolower($cus_type)).' '.trans('common.due_report'),
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

            {!! Form::open(['route' => ['member.report.inventory_due', strtolower($type)],'method' => 'GET', 'role'=>'form' ]) !!}

                <div class="box-body">
                    <div class="row">
                        @if(Auth::user()->hasRole(['super-admin', 'developer']))
                            <div class="col-md-3">
                                <label>  {{__('common.select_company')}} </label>
                                {!! Form::select('company_id', $companies, null,['id'=>'company_id','class'=>'form-control select2','placeholder'=>trans('common.select_company')]); !!}
                            </div>
                        @endif
                        <div class="col-md-4">
                            <label> {{ $cus_type == "Sale" ? trans('common.customers') : trans('common.suppliers')}} </label>
                            {!! Form::select('sharer_id', $sharers, null,['id'=>'sharer_id','class'=>'form-control select2','placeholder'=>trans('common.select_all')]); !!}
                        </div>
                        @if($type=="Sale" || $type=="Purchase")
                        <div class="col-md-2">
                            <label> {{__('common.from_date')}} </label>
                            <input class="form-control date" name="from_date" value="" autocomplete="off"/>
                        </div>
                        <div class="col-md-2">
                            <label> {{__('common.to_date')}}</label>
                            <input class="form-control date" name="to_date" value="" autocomplete="off"/>
                        </div>
                        @endif
                        <div class="col-md-2 margin-top-23">
                            <label></label>
                            <input class="btn btn-sm btn-info" value="{{__('common.search')}}" type="submit"/>
                            <a href="{{ route(Route::current()->getName(), $type) }}" class="btn btn-sm btn-primary"><i class="fa fa-refresh"></i> {{__('common.reload')}}</a>

                        </div>
                    </div>
                    <!-- /.row -->
                </div>

                {!! Form::close() !!}
            </div>

            <div class="box">
                <div class="box-header with-border">
                    {{-- <h3 class="box-title">{{ ucfirst($type) }} Due Report</h3> --}}
                    <h3 class="box-title">{{ trans('common.'.strtolower($type)) }} {{__('common.due_report')}}</h3>
                    <a href="{{ $full_url}}type=print" class="btn btn-sm btn-primary pull-right" id="btn-print"> <i class="fa fa-print"></i> {{__('common.print')}} </a>
                    {{-- <a href="{{ $full_url}}type=download" class="btn btn-sm btn-success pull-right mx-3"> <i class="fa fa-download"></i> {{__('common.download')}} </a> --}}
                </div>

                <div class="box-body">

                    <div class="col-lg-12">
                        <table class="table table-striped" id="dataTable">

                            <tbody>
                            <tr>
                                <th  class="text-center" > #{{__('common.serial')}}</th>
                                <th> {{ $type=="customer" || $type=="Sale" ? trans('common.customer') : trans('common.supplier') }} {{__('common.name')}}</th>
                                <th class="text-right"> {{__('common.paid_amount')}}</th>
                                <th class="text-right"> {{__('common.due_amount')}}</th>
                                <th class="text-right"> {{__('common.discount_amount')}}</th>
                                <th class="text-right"> {{__("common.total_amount")}}</th>
                                <th  class="text-center"> {{__('common.date')}}</th>
                            </tr>
                            @foreach($modal as $key=>$value)
                                <tr>
                                    <td  class="text-center"> {{ $key+1 }} </td>
                                    @if($type=="customer" || $type=="Sale")
                                        <td class="text-left" > {{ $value->customer ? $value->customer->name : '' }} </td>
                                        <td class="text-right" > {{ create_money_format($value->paid_amount) }} </td>
                                        <td class="text-right" > {{ create_money_format($value->due) }} </td>
                                        <td class="text-right" > {{ create_money_format($value->total_discount) }} </td>
                                        <td class="text-right" > {{ create_money_format($value->total_price) }} </td>
                                    @else
                                        <td class="text-left"> {{ $value->supplier ? $value->supplier->name : '' }} </td>
                                        <td class="text-right" > {{ create_money_format($value->paid_amount) }} </td>
                                        <td class="text-right" > {{ create_money_format($value->due_amount) }} </td>
                                        <td class="text-right" > {{ create_money_format($value->total_discount) }} </td>
                                        <td class="text-right" > {{ create_money_format($value->total_amount) }} </td>
                                    @endif
                                    <td class="text-center"> {{ $value->date_format }} </td>
                                </tr>
                            @endforeach

                            @if(count($modal)<1)
                                <tr>
                                    <td colspan="7"> No Data Available </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="col-lg-12 col-sm-12 col-md-12 text-right">
                        {{ $modal->links() }}
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
